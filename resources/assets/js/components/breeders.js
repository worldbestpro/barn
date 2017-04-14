(function () {

    App.emptyBreeder = {
        "id": 0,
        "name": '',
        "breed": '',
        "cage": '',
        "tattoo": '',
        "sex": 'doe',
        "weight": '',
        "father_id": 0,
        "mother_id": 0,
        "color": '',
        "aquired": moment(new Date).format(App.dateFormat),
        "image": {
            "name": '',
            "path": '',
            "temp": true,
            "oldImage": '',
            "delete": false,
        },
        "notes": ''
    };


    App.Components.Breeders = {
        template: "#breeders-template",
        data: function () {
            return {
                breeder: _.extend({}, App.emptyBreeder),
                breeders: [],
                pages: 1,
                activeBreeder: {},
                loading: 1,
                plans: [],
                activeBirth: {},
                loading: 0,
                litters: [],
                "order": null

        }
        },
        props: [],
        components: {
            'breeder-form': App.Components.BreederForm,
            'litter-weight': App.Components.LitterWeight,
            'litter-butcher': App.Components.LitterButcher
        },
        computed: {
            filter: function () {
                return this.$route.params.action;
            },
            page: function () {
                return this.$route.query.page || 1;
            },
            currentRoute: function () {
                return this.$route.path.split('?')[0];
            },
            confirmTarget: function () {
                return this.activeBreeder.name;
            },
        },

        watch: {
            filter: function () {
                this.updateList();
            },
            page: function () {
                this.updateList();
            },
            order: function () {
                this.updateList();
            }
        },

        methods: {

            updateList: function () {
                var data = { page: this.page };
                if(this.order){
                    data.order = this.order;
                }

                if(this.filter == "archive") {
                    data.archived = 1;
                } else {
                    data.archived = 0;
                    if(this.filter) {
                        data.sex = this.filter == "bucks" ? "buck" : "doe";
                    }
                }
                this.$http.get('/admin/breeders', data, function (data) {
                    this.loading = 0;
                    this.breeders = data.breeders.data;
                    this.pages = data.breeders.last_page;
                    this.order = data.order;
                });
            },

            addNew: function () {
                App.vent.trigger('breeders.modal.open');
                this.breeder = _.extend({}, App.emptyBreeder);
                $('#breeder-form').modal('show');
            },

            editModal: function (breeder) {
                App.vent.trigger('breeders.modal.open');
                this.breeder = _.extend({}, breeder);
                $('#breeder-form').modal('show');
            },

            initUploader: function () {
                var self = this;
                $(self.$els['image']).fileupload({
                    dataType: 'json',
                    paramName: 'image',
                    formData: {
                        "_token": App.token,
                        "_method": "POST",
                    },
                    url: '/admin/images/uploadImage',
                    done: function (e, data) {
                        self.breeder.image.name = data.result.image.name;
                        self.breeder.image.path = data.result.image.path;
                        self.breeder.image.temp = data.result.image.temp;
                    }
                });
            },

            getGenderClass: function (sex) {
                return sex == "buck" ? "bg-aqua-active" : "bg-maroon-active";
            },

            onlyNumbers: function (e) {
                return e.charCode >= 48 && e.charCode <= 57;
            },

            prevPage: function () {
                if (this.page - 1 > 0) {
                    this.$router.go({
                        path: this.currentRoute,
                        query: {
                            page: this.page - 1
                        }
                    });
                }
            },

            nextPage: function () {
                if (Number(this.page) + 1 <= this.pages) {
                    this.$router.go({
                        path: this.currentRoute,
                        query: {
                            page: Number(this.page) + 1
                        }
                    });
                }
            },

            deleteImage: function () {
                this.breeder.image = {name: "", path: "", temp: true, oldImage: "", delete: false};
            },



            archiveModal: function (breeder) {
                this.activeBreeder = breeder;
                $('#archive-breed-modal').modal('show');
            },
            archive: function () {
                $('#archive-breed-modal').modal('hide');
                this.$http.post('/admin/breeders/'+this.activeBreeder.id+'/archive', { archived: 1 }, function (res) {
                    $('#id_'+this.activeBreeder.id).slideUp(200);
                });
            },

            unarchiveModal: function (breeder) {
                this.activeBreeder = breeder;
                $('#unarchive-breed-modal').modal('show');
            },
            unarchive: function () {
                $('#unarchive-breed-modal').modal('hide');
                this.$http.post('/admin/breeders/'+this.activeBreeder.id+'/archive', { archived: 0 }, function (res) {
                    $('#id_'+this.activeBreeder.id).slideUp(200);
                });
            },

            deleteModal: function (breeder) {
                this.activeBreeder = breeder;
                $('#delete-breed-modal').modal('show');
            },
            delete: function () {
                $('#delete-breed-modal').modal('hide');
                this.$http.post('/admin/breeders/' + this.activeBreeder.id, { _method: "DELETE" }, function (res) {
                    $('#id_'+this.activeBreeder.id).slideUp(200);
                });
            },
            birthModal: function () {
                this.$http.get('/admin/users/plans', {}, function (plans) {
                    this.plans = plans;
                });
                this.activeBirth = {breedplan: '-1', born: moment(new Date()).format(App.dateFormat)};
            },
            recordBirth: function () {
                this.$http.post('/admin/litters', this.activeBirth, function (res) {
                    this.activeBirth = {};
                    $('#birth').modal('hide');
                });
            },
            loadLitters: function () {
                var data = { page: this.page };
                data.archived = this.filter == "archive" ? 1 : 0;

                this.$http.get('/admin/litters', data, function (litters) {
                    this.loading = 0;
                    this.litters = litters.data;
                });
            },
        },
        ready: function () {
            this.initUploader();
            this.updateList();
            App.vent.on('breeders.new', this.addNew.bind(this));
            if (this.$route.params.action == 'new') {
                this.addNew();
            }
            App.MobileTypes();
            this.birthModal();
            this.loadLitters();
        }

    };

})();