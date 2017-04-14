(function () {

    App.Components.BreederForm = {
        template: "#breeder-form-template",
        data: function () {
            return {
                bucks: [],
                does: [],
                newBuck: {
                    sex: "buck"
                },
                newDoe: {
                    sex: "doe"
                },
                errors: {},
                warnings: {},
                breeds: []
            }
        },
        props: ['breeder','breeders'],
        components: {
            'image-upload': App.Components.ImageUpload
        },
        computed: {
            action: function () {
                if (this.breeder.id) {
                    return '/admin/breeders/' + this.breeder.id;
                } else {
                    return '/admin/breeders';
                }
            }
        },
        watch: {
            breeder: function () {
                $('.js_icheck-breeder-blue, .js_icheck-breeder-red').iCheck('update');
                $('#breeder-cage').typeahead('val', this.breeder.cage);
                $('#breeder-tattoo').typeahead('val', this.breeder.tattoo);
                $('#breeder-color').typeahead('val', this.breeder.color);
                $('#breeder-breed').typeahead('val', this.breeder.breed);
            }
        },
        methods: {
            uniqueFieldSet: function(field){
                return _.unique(_.pluck(_.flatten([].concat(this.does, this.bucks)), field));
            },
            initModal: function () {
                this.$http.get('/admin/breeders/getList', function (breeders) {
                    this.bucks = breeders.bucks;
                    this.does = breeders.does;
                    var breeds = this.uniqueFieldSet("breed");
                    var tattooes = this.uniqueFieldSet("tattoo");
                    var cages = this.uniqueFieldSet("cage");
                    var colors = this.uniqueFieldSet("color");

                    $('#breeder-breed').typeahead({
                        hint: true,
                        highlight: true,
                        minLength: 0
                    },
                    {
                        source: this.substringMatcher(breeds)
                    });

                    $('#breeder-cage').typeahead({
                        hint: true,
                        highlight: true,
                        minLength: 0
                    },
                    {
                        source: this.substringMatcher(cages)
                    });


                    $('#breeder-tattoo').typeahead({
                        hint: true,
                        highlight: true,
                        minLength: 0
                    },
                    {
                        source: this.substringMatcher(tattooes)
                    });
                    $('#breeder-color').typeahead({
                        hint: true,
                        highlight: true,
                        minLength: 0
                    },
                    {
                        source: this.substringMatcher(colors)
                    });

                });
            },

            sendBreeder: function () {
                var breeder = this.breeder;

                if(breeder.id != 0) {
                    breeder._method = "PUT";
                }

                this.$http.post(this.action, breeder, function (breederResponse) {
                    $('#breeder-form').modal('hide');
                    console.log(breeder.aquired);
                    if (breeder.id == 0) {
                        this.breeders.push(breederResponse);
                    } else {
                        var match = _.find(this.breeders, function (item) {
                            return item.id === breeder.id
                        });
                        if (match) {
                            _.extendOwn(match, breederResponse)
                        }
                        this.breeder = breederResponse;
                    }
                    this.closeModal();
                }.bind(this)).error(function (errors) {
                    this.errors = errors;
                });

            },

            checkDoubledId: function () {
                this.$http.get('/admin/breeders/checkId', { id: this.breeder.tattoo }, function (check) {
                    if(check.idDoubled) {
                        this.warnings = { tattoo: ['Breeder ID is duplicated'] };
                    } else {
                        this.warnings = {};
                    }
                });
            },


            addNewBuck: function () {
                this.$http.post('/admin/breeders', _.extend({}, App.emptyBreeder, this.newBuck), function (breederResponse) {
                    this.bucks.push(breederResponse);
                    this.breeder.father_id = breederResponse.id;
                    this.newBuck = { sex: "buck" };
                });
            },

            addNewDoe: function () {
                this.$http.post('/admin/breeders', $.extend({}, App.emptyBreeder, this.newDoe), function (breederResponse) {
                    this.does.push(breederResponse);
                    this.breeder.mother_id = breederResponse.id;
                    this.newDoe = { sex: "doe" };
                });
            },

            substringMatcher: function(strs) {
                return function findMatches(q, cb) {
                    var matches, substringRegex;

                    // an array that will be populated with substring matches
                    matches = [];

                    // regex used to determine if a string contains the substring `q`
                    substrRegex = new RegExp(q, 'i');

                    // iterate through the pool of strings and for any string that
                    // contains the substring `q`, add it to the `matches` array
                    $.each(strs, function(i, str) {
                        if (substrRegex.test(str)) {
                            matches.push(str);
                        }
                    });

                    cb(matches);
                };
            }
        },
        ready: function () {
            this.initModal();

            $('.js_icheck-breeder-blue').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue'
            }).on('ifChecked', function(event){
                this.breeder.sex = "buck";
            }.bind(this));

            //Red color scheme for iCheck
            $('.js_icheck-breeder-red').iCheck({
                checkboxClass: 'icheckbox_square-red',
                radioClass: 'iradio_square-red'
            }).on('ifChecked', function(event){
                this.breeder.sex = "doe";
            }.bind(this));
        }

    };

})();