(function () {

    App.Components.LitterButcher = {
        template: "#litter-butcher-template",
        data: function () {
            return {
                errors: {},
                date: moment(new Date).format(App.dateFormat),
                loaded: 0,
                current: 0,
                selectedLitter: 0
            }
        },
        props: ['litters', 'litter', 'kits'],
        watch: {
            litter: function (a, b) {
                if(this.litter) {
                    this.selectedLitter = this.litter.id;
                    $(this.$els.checkall).prop('checked', false);
                    if(b && this.selectedLitter) {
                        this.$http.get('admin/litters/' + this.selectedLitter + '/weigh?animal_type=rabbitkit', {}, function (kits) {
                            this.kits = kits;
                        });
                    }
                }
            },
            selectedLitter: function (a, b) {
                if(b || (this.litter && !this.litter.id)) {
                    this.litter = _.find(this.litters, function (item) {
                        return item.id == this.selectedLitter;
                    }.bind(this));
                }
            }
        },
        methods: {
            getGenderClass: function (sex) {
                if (!sex) {
                    return "bg-gray-active";
                }
                return sex == "buck" ? "bg-aqua" : "bg-maroon";
            },
            sendToButcher: function () {
                var selectedKits = _.filter(this.kits, function (kit) {
                    return kit.selected;
                });

                var data = {
                    kits: selectedKits,
                    date: this.date,
                    litter_id: this.selectedLitter
                };

                this.$http.post('admin/kits/butch', data, function (res) {
                    this.$dispatch('refresh-kits');
                });

                $('#litter-butcher-modal').modal('hide');
            },

        },
        ready: function () {

            $(this.$els.checkall).on('click', function () {
                if(this.checked) {
                    $('.js_butcher_checkbox').prop('checked', false).trigger('click');
                } else {
                    $('.js_butcher_checkbox').prop('checked', true).trigger('click');
                }
            });

        }

    };

})();