(function () {

    App.Components.LitterForm = {
        template: "#litter-form-template",
        data: function () {
            return {
                bucks: [],
                does: [],
                errors: {}
            }
        },
        props: ['litter','litters'],
        computed: {
            action: function () {
                if (this.litter.id) {
                    return '/admin/litters/' + this.litter.id;
                } else {
                    return '/admin/litters';
                }
            },
        },
        methods: {
            initModal: function () {
                this.$http.get('/admin/breeders/getList', function (breeders) {
                    this.bucks = breeders.bucks;
                    this.does = breeders.does;
                });
            },

            sendLitter: function () {
                var litter = this.litter;
                litter.animal_type='rabbit';

                if(litter.id != 0) {
                    litter._method = "PUT";
                }

                this.$http.post(this.action, litter, function (litterResponse) {
                    $('#litter-form').modal('hide');
                    if (litter.id == 0) {
                        litterResponse.archived = 0;
                        this.litters.push(litterResponse);
                    } else {
                        this.litter = litterResponse;
                    }
                    this.$dispatch('litter-updated', this.litter.id);
                    this.closeModal();
                }).error(function (errors) {
                    this.errors = errors;
                });

            }

        },
        ready: function () {
            this.initModal();
        }

    };

})();