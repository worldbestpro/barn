(function () {

    App.Components.LitterBox = {
        template: "#litter-box-template",
        data: function () {
            return {
                kits: [],
            }
        },
        props: ['litter', 'activeLitter'],
        components: {
        },
        computed: {
            age_weeks: function () {
                var born = moment(this.litter.born, App.dateFormat);
                var now = moment().startOf('day');

                age = now.diff(born, "days");
                var weeks = Math.round(age / 7);
                if (age < 7) {
                    return "Less than a week";
                } else {
                    return weeks + " week" + (weeks === 1 ? "" : "s");
                }

                return weeks;
            },
            age_days: function () {
                var born = moment(this.litter.born, App.dateFormat);
                var now = moment().startOf('day');
                var days = now.diff(born, "days");
                return days <= 0 ? "Today" : days + ' days';
            }
        },
        methods: {
            getGenderClass: function (kit) {
                if(this.isArchived(kit)) {
                    return "bg-gray";
                }
                if(!kit.sex || this.isButchered(kit)) {
                    return "bg-gray-active";
                }
                if(kit.improved == 1) {
                    return "bg-gray-active";
                }
                return kit.sex == "buck" ? "bg-aqua" : "bg-maroon";
            },
            getKits: function () {
                this.$http.get('admin/litters/'+this.litter.id+'/getKits', function(kits){
                    this.kits = kits;
                    var alive = 0;
                    $.each(kits, function (i, val) {
                        if(val.alive == 1 && val.archived == 0) {
                            alive++;
                        }
                    });
                    this.$dispatch('alive-kits', alive);
                });
            },
            showWeights: function (kit) {
                var weight = '';
                $.each(kit.weight, function (i) {
                    if(kit.weight[i]) {
                        weight += kit.weight[i] + '-';
                    }
                });
                return weight.substring(0, weight.length - 1);
            },

            editKitModal: function (kit) {
                this.$dispatch('edit-kit', kit);
            },
            
            isButchered: function (kit) {
                return kit.alive == 0 && kit.survived == 1 && kit.improved == 0 && kit.archived != 1;
            },

            isArchived: function (kit) {
                return kit.archived == 1;
            }

        },
        ready: function () {
            this.getKits();

            this.$on('refresh-kits-global', function (id) {
                if(id == this.litter.id) {
                    this.getKits();
                }
            });
        }

    };

})();