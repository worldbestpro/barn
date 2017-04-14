(function () {

    App.emptyLitter = {
        "id": 0,
        "given_id": '',
        "kits_amount": '',
        "kits":[],
        "father_id": 0,
        "mother_id": 0,
        "bred": moment(new Date).format(App.dateFormat),
        "born": moment(new Date).format(App.dateFormat),
        "notes": '',
    };


    App.Components.Litters = {
        template: "#litters-template",
        data: function () {
            return {
                litter: _.extend({}, App.emptyLitter),
                litters: [],
                pages: 1,
                activeLitter: {},
                activeKits: [],
                loading: 1,
                plans: [],
                activeBirth: {},
                order: null
            }
        },
        props: [],
        components: {
            'litter-form': App.Components.LitterForm,
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
                return "this litter";
            }
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
            age: function (date) {
                var born = moment(date, App.dateFormat);
                var now = moment().startOf('day');

                age = now.diff(born, "days");
                var weeks = Math.round(age / 7);
                if (age < 1) {
                    return "Today";
                }
                if (age < 7) {
                    return age + " days";
                } else {
                    return weeks + " week" + (weeks === 1 ? "" : "s");
                }
                return weeks;
            },
            updateList: function () {
                var data = { page: this.page};
                if(this.order){
                    data.order = this.order;
                }
                data.archived = this.filter == "archive" ? 1 : 0;

                this.$http.get('/admin/litters', data, function (data) {
                    this.loading = 0;
                    this.litters = data.litters.data;
                    this.pages = data.litters.last_page;
                    this.order = data.order;
                });
            },

            newLitter: function () {
                this.litter = _.extend({}, App.emptyLitter);
                $('#litter-form').modal('show');
            },

            getGenderClass: function (sex) {
                return sex == "male" ? "bg-aqua-active" : "bg-maroon-active";
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


            editModal: function (litter) {
                this.litter = litter;
                $('#litter-form').modal('show');
            },

            loadKits: function () {
                this.$http.get('admin/litters/' + this.activeLitter.id + '/weigh?animal_type=rabbitkit', {}, function (kits) {
                    this.activeKits = kits;
                });
            },

            weightModal: function (litter) {
                this.activeLitter = litter;
                //this.loadKits();
                $('#litter-weight-modal').modal('show');
            },

            butcherModal: function (litter) {
                this.activeLitter = litter;
                //this.loadKits();
                $('#litter-butcher-modal').modal('show');
            },

            archiveModal: function (litter) {
                this.activelitter = litter;
                $('#archive-breed-modal').modal('show');
            },
            archive: function () {
                $('#archive-breed-modal').modal('hide');
                this.$http.post('/admin/litters/' + this.activelitter.id + '/archive', {archived: 1}, function (res) {
                    $('#id_' + this.activelitter.id).slideUp(200);
                });
            },

            unarchiveModal: function (litter) {
                this.activelitter = litter;
                $('#unarchive-breed-modal').modal('show');
            },
            unarchive: function () {
                $('#unarchive-breed-modal').modal('hide');
                this.$http.post('/admin/litters/' + this.activelitter.id + '/archive', {archived: 0}, function (res) {
                    $('#id_' + this.activelitter.id).slideUp(200);
                });
            },

            deleteModal: function (litter) {
                this.activelitter = litter;
                $('#delete-breed-modal').modal('show');
            },
            delete: function () {
                $('#delete-breed-modal').modal('hide');
                this.$http.post('/admin/litters/' + this.activelitter.id, {_method: "DELETE"}, function (res) {
                    $('#id_' + this.activelitter.id).slideUp(200);
                });
            },

            calcKits: function (litter) {
                return litter.kits_died ? litter.kits_amount - litter.kits_died : litter.kits_amount;
            },


            father: function (parents) {
                return _.find(parents, function (item) { return item.sex == "buck" });
            },
            mother: function (parents) {
                return _.find(parents, function (item) { return item.sex == "doe" });
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


        },
        ready: function () {
            this.updateList();
            App.MobileTypes();
            App.vent.on('litters.new', this.newLitter.bind(this));
            if (this.$route.params.action == 'new') {
                this.newLitter();
            }
            this.$on('weighted', this.updateList.bind(this));this.birthModal();
            this.birthModal();
        }

    };

})();