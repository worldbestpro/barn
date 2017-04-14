(function () {

    App.Components.LitterWeight = {
        template: "#litter-weight-template",
        data: function () {
            return {
                bucks: [],
                does: [],
                errors: {},
                date: moment(new Date).format(App.dateFormat),
                activeKit: {},
                loaded: 0,
                current: 0,
                showNavigator: 1,

                generate: false,
                listen: 0,
                changed: -1,
                selectedLitter: 0,
                weighedKits: 0
            }
        },
        props: ['litters', 'litter', 'kits'],
        components: {
            'image-upload': App.Components.ImageUpload,
            'sex-select': App.Components.SexSelect
        },
        computed: {
            action: function () {
                if (this.litter.id) {
                    return '/admin/litters/' + this.litter.id;
                } else {
                    return '/admin/litters';
                }
            },
            first: function () {
                return this.litter.id && !this.litter.total_weight;
            }
        },
        watch: {
            litter: function () {
                if(this.litter && this.litter.id) {
                    this.$http.get('admin/litters/' + this.litter.id + '/weigh?animal_type=rabbitkit', {}, function (kits) {
                        this.kits = kits;
                    });
                    this.selectedLitter = this.litter.id;
                }
            },
            selectedLitter: function (a, b) {
                this.litter = _.find(this.litters, function (item) {
                    return item.id == this.selectedLitter;
                }.bind(this));
            },
            kits: function () {
                var self = this;
                this.activeKit = this.kits[this.current];
                this.loaded = 1;
                this.weighedKits = 0;
                $.each(this.kits, function (i, kit) {
                    if(kit.current_weight){
                        self.weighedKits++;
                    }
                });
            },
            activeKit: function () {
                if(this.first) {
                    $('.info-box.active').removeClass('active');
                    $('#kit-'+this.activeKit.id).addClass('active');
                    this.changed++;
                }
            },
            current: function () {
                if(this.kits[this.current]) {
                    this.activeKit = this.kits[this.current];
                } else {
                    this.current = this.kits.length-1;
                }
            },
            first: function () {
                $(this.$els.form).find('input, select, textarea').on('change', function () {
                    this.changed++;
                }.bind(this));
                $('.icheck-group div, .icheck-group label').on('click', function () {
                    this.changed++;
                }.bind(this));
            }
        },
        methods: {
            getGenderClass: function (sex) {
                if (!sex) {
                    return "bg-gray-active";
                }
                return sex == "buck" ? "bg-aqua" : "bg-maroon";
            },

            generateIds: function () {
                var self = this;
                if(this.current == 0 && this.generate){
                    $.each(this.kits, function (i) {
                        if(i){
                            this.given_id = isNaN(parseInt(self.kits[0].given_id)) ? self.kits[0].given_id + i: parseInt(self.kits[0].given_id) + i;
                        }
                    });
                }
            },

            showWeights: function (kit) {
                var weight = '';
                if(kit.weight) {
                    $.each(kit.weight, function (i) {
                        weight += kit.weight[i] + '-';
                    });
                    return weight.substring(0, weight.length - 1);
                } else {
                    return kit.current_weight;
                }
            },

            saveAll: function () {

                if(this.first && this.changed) {
                    this.saveKit(_.extend({}, this.activeKit), this.sendKits.bind(this));
                } else {
                    this.sendKits();
                }

            },

            sendKits: function () {
                this.errors = {};

                var data = {
                    date: this.date,
                    kits: this.kits,
                    kits_weighed: this.weighedKits
                };

                this.$http.post('admin/litters/' + this.litter.id + '/weigh', data, function (res) {
                    this.$dispatch('refresh-kits');
                    this.$dispatch('weighted');
                    $('#litter-weight-modal').modal('hide');
                }).error(function (errors) {
                    this.$dispatch('refresh-kits');
                    this.$dispatch('weighted');
                    $('#litter-weight-modal').modal('hide');
                    this.errors = errors;
                });
            },

            diedKit: function (kit) {
                $('#kit-'+kit.id).fadeOut(500);
                this.kits = _.reject(this.kits, function(_kit){ return _kit.id == kit.id; });
                this.$http.get('admin/kits/' + kit.id + '/died');
                return false;
            },

            setActiveKit: function (kit, index) {
                if(this.first) {
                    this.changed && this.saveKit( _.extend({}, this.activeKit) );
                    this.changed = -1;

                    this.activeKit = kit;
                    this.current = index;
                }
            },


            saveWeight: function (kit) {
                $('#kit-'+kit.id).fadeOut(500);
                //this.saveKit(kit);
                this.weighedKits++;
            },

            saveKit: function (kit, callback) {
                var _kit = kit || _.extend({}, this.activeKit);

                if(_kit.weight == null) {
                    _kit.weight = [];
                }
                _kit.weight.push(_kit.current_weight);

                _kit._method = "PUT";
                _kit.return_count = 1;
                //_kit.weight_changed = 1;//TODO this trigger is important for kit editing (not during first weigh)
                this.$http.post('admin/kits/'+_kit.id, _kit, function (res) {
                    this.weighedKits++;
                    if(typeof callback == "function") {
                        callback(res);
                    } else {
                        if(this.kits.length == this.weighedKits) {
                            this.sendKits();
                        }
                    }
                });
            },


            prevKit: function () {
                if(this.kits.length > 1) {
                    this.changed && this.saveKit();
                }
                this.changed = -1;

                this.current--;
            },
            nextKit: function () {
                if(this.kits.length > 1) {
                    this.changed && this.saveKit();
                }
                this.changed = -1;
                this.generateIds();

                if(this.current+1 >= this.kits.length) {
                    this.current = 0;
                } else {
                    this.current++;
                }
            },

            diedNextKit: function () {
                this.diedKit(this.activeKit);
                if(this.current >= this.kits.length) {
                    this.current = 0;
                }
                this.changed = -1;
            },

            imageUploaded: function (image) {
                this.activeKit.image = image;
            },

            father: function () {
                return _.find(this.litter.parents, function (item) { return item.sex == "buck" });
            },
            mother: function () {
                return _.find(this.litter.parents, function (item) { return item.sex == "doe" });
            },

        },
        ready: function () {

            $(this.$els.form).find('input, select, textarea').on('change', function () {
                this.changed++;
            }.bind(this));
            $('.icheck-group div, .icheck-group label, .image-remove-icon').on('click', function () {
                this.changed++;
            }.bind(this));

        }

    };

})();