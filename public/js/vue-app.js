(function () {

    App.dateFormat = "MM/DD/YYYY";


})();
(function () {

    App.Components.SexSelect = {
        template: "#sex-select-template",
        data: function () {
            return {
            }
        },
        props: ['model'],
        computed: {
        },
        watch: {
            model: function () {
                $(this.$els.buck).iCheck('update');
                $(this.$els.doe).iCheck('update');
            }
        },
        methods: {
        },
        ready: function () {

            $(this.$els.buck).iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue'
            }).on('ifChecked', function(event){
                this.model.sex = "buck";
            }.bind(this));

            //Red color scheme for iCheck
            $(this.$els.doe).iCheck({
                checkboxClass: 'icheckbox_square-red',
                radioClass: 'iradio_square-red'
            }).on('ifChecked', function(event){
                this.model.sex = "doe";
            }.bind(this));
        }

    };

})();
(function () {

    App.Components.ImageUpload = {
        template: "#image-upload-template",
        data: function () {
            return {
                progress: 0,
                loading: 0
            }
        },
        props: ['breeder'],
        watch: {
/*
            breeder: function () {
                this.initUploader();
            }
*/
        },
        computed: {
        },
        methods: {
            initUploader: function () {
                var self = this;
                $(this.$els['image']).fileupload({
                    dataType: 'json',
                    paramName: 'image',
                    formData: {
                        _method: "POST",
                        _token: App.token
                    },
                    url: '/admin/images/uploadImage',
                    send: function () {
                        self.loading = 1;
                    },
                    done: function (e, data) {
                        self.loading = 0;
                        if(this.breeder.image) {
                            this.breeder.image.name = data.result.image.name;
                            this.breeder.image.path = data.result.image.path;
                            this.breeder.image.temp = data.result.image.temp;
                        }
                        this.$broadcast('image-uploaded', data.result.image);
                    }.bind(this)
                });
            },
            uploaderHelper: function () {
                $('#breeder-fileupload').click();
            },
            deleteImage: function () {
                this.breeder.image = {name: "", path: "", temp: true, oldImage: "", delete: false};
                $('#breeder-fileupload').change();
            }
        },
        ready: function () {
            this.initUploader();
        }

    };

})();

Vue.directive('datepicker', {
    twoWay: true,
    bind: function (value) {
        var vm = this.vm;
        var key = this.expression;
        var self = this;
        var disable_past = $(this.el).find('input').data('disable-past');
        $(this.el).datepicker({
            format: App.dateFormat.toLowerCase(),
            todayBtn: "linked",
            autoclose: true,
            todayHighlight: true,
            disableTouchKeyboard: true,
            toggleActive: true,
            defaultDate: new Date(),
            startDate: disable_past ? new Date() : 0
        }).on('show', function(e) {
            /**
             * hack to disable touch keyboard with blur() method,
             * but only if the datepicker is not visible (to minimize accessibility problems)
             * so with a second click, the keyboard appears
             */
            //if ((window.navigator.msMaxTouchPoints || 'ontouchstart' in document)) {
            //    if (!isDatepickerVisible) {
            //        $('input', $(this))[0].blur();
            //    }
            //}
            //isDatepickerVisible = true;

        }).on('hide', function(e) {
            //isDatepickerVisible = false;
        }).on('changeDate', function(e){
            self.vm.$set(key, e.format());
        });

        $(this.el).datepicker('setDate', this.value);

    },
    update: function (value) {
        $(this.el).val(value);
        $(this.el).datepicker('setDate', value);
    },
    unbind: function () {
        $(this.el).datepicker('destroy');
    }
});

(function () {

    App.Components.TaskForm = {
        template: "#task-form-template",
        data: function () {
            return {
                originalTask:{},
                rabbits: [],
                breeder_litter: [],
                week: [],
                label : 'label',
                label_color : 'label-danger',
                activeRabbit: {},
                info_panel_class : ' bg-info',
                button_class : ' btn-primary',
                name_of_modal: 'New Task',
                modeOfTask: 'create',
                errors: { name: null, date: null, icon: null},
                type_of_tasks: 'all',
                iconBackground: {'bg-red': false, 'bg-blue': false, 'bg-maroon': false, 'bg-green': false, 'bg-yellow': false, 'bg-grey': false, 'fa-calendar' : false, 'fa-heart' : false, 'fa-asterisk' : false, 'fa-bookmark' : false, 'fa-eye' : false, 'fa-flag' : false, 'fa-medkit' : false, 'fa-paw' : false, 'fa-trophy' : false}
            }
        },
        props: {
            activeTask:{
                type: Object,
                default: function () {
                    return {}
                }
            }
        },
        computed: {},
        watch:{
            'activeTask.icon': function(newValue, oldValue){
                if(newValue){
                    var newClass = newValue.split(' ')[1];
                    var oldClass = oldValue ? oldValue.split(' ')[1] : '';

                    if(newClass == 'bg-gray') newClass = newValue.split(' ')[0];
                    if(oldClass == 'bg-gray') oldClass = oldValue ? oldValue.split(' ')[0] : '';

                    this.iconBackground[newClass] = true;
                    if(oldValue) this.iconBackground[oldClass] = false;
                }
            },
            'activeTask.type': function(newValue, oldValue){
                switch(newValue){
                    case 'breeder': {this.loadBreeders(); break;}
                    case 'litter': {this.loadLitters(); break;}
                    default:{this.breeder_litter = [];}
                }
            },
        },
        events: {
            'tasks-managing': function (task) {
                this.openTaskForm(task);
            },
            'tasks-delete-task': function(msg){
                this.showRequestForm(msg);
            }
        },
        methods: {
            capitalize : function (string) {
                return string.replace(/^./, function (match) {
                    return match.toUpperCase();
                });
            },
            openTaskForm: function(task){
                if(task){
                    this.info_panel_class = "bg-info";
                    this.name_of_modal = "Edit Task";
                    this.button_class = "btn-primary";
                    this.modeOfTask = 'edit';
                    this.$http.get('/admin/events/'+task.id, {}, function (task) {
                        this.activeTask = $.extend({}, task);
                        this.originalTask = $.extend({}, task);
                        this.activeTask.relation = {};
                        if(this.activeTask.litters || this.activeTask.breeders){
                            this.activeTask.relation = this.activeTask.litters ? this.activeTask.litters[0] : this.activeTask.breeders[0];
                        }
                    });

                } else {
                    this.modeOfTask = 'create';
                    this.name_of_modal = "Create Task";
                    this.info_panel_class = "bg-success";
                    this.button_class = "btn-success";
                    this.errors = { name: null, date: null, icon: null};
                    this.activeTask = {'name': null,'date': App.formatDate(new Date), 'recurring': "1", 'type': "general", 'icon' : 'fa-cutlery bg-red', relation: {id: ''}};
                }

                $('#new_task').modal('show');
            },
            createTask: function(mode){
                var self = this;
                if(this.$activeTaskValidator.touched && this.$activeTaskValidator.invalid) return;

                if(mode == 'edit'){
                    this.activeTask.type_id = this.activeTask.relation.id || null;
                    this.activeTask.type_changed = (this.originalTask.type !== this.activeTask.type) || (this.originalTask.type_id !== this.activeTask.type_id);
                    this.$http.put('/admin/events/'+ this.activeTask.id, this.activeTask, function(data){
                        if(self.activeTask.closed == '0'){
                            self.$http.get('/admin/events/' + self.activeTask.id + '/reopen', function(data){});
                        } else {
                            self.$http.get('/admin/events/' + self.activeTask.id + '/close', function(data){});
                        }
                        this.$dispatch('reload-tasks', {typeOfTask: this.type_of_task, page: this.page});
                    });
                } else {
                    this.activeTask._method = "POST";
                    this.activeTask.type_id = this.activeTask.relation ? this.activeTask.relation.id : null;
                    this.$http.post('/admin/events', this.activeTask, function(data){
                        this.$dispatch('reload-tasks', {typeOfTask: this.type_of_task, page: this.page});
                    });
                }

                $('#new_task').modal('hide');
            },
            finishTask: function(task){
                if(task.closed == '1'){
                    this.$http.get('/admin/events/' + task.id + '/reopen', function(data){
                        task.closed = '0';
                    });
                } else {
                    this.$http.get('/admin/events/' + task.id + '/close', function(data){
                        task.closed = '1';
                    });
                }
            },
            getFullTaskName: function(task){
                if(task.holderName){
                    if(task.type == 'breeder'){
                        return task.holderName + ': ' + task.name;
                    }
                    if(task.type == 'litter'){
                        return 'Litter ' + task.holderName + ': ' + task.name;
                    }
                }
                return task.name;
            },
            loadBreeders: function(){
                this.$http.get('/admin/breeders/getList', {}, function (breeders) {
                    this.breeder_litter = breeders.bucks.concat(breeders.does);
                });
            },
            loadLitters: function(){
                this.$http.get('/admin/litters/getList', {}, function (litters) {
                    var tempRabbits = [];
                    litters.forEach(function(item, index, arr){
                        var name = item.parents[0].name + '/' + item.parents[1].name;
                        tempRabbits.push({'given_id' : item.given_id, 'id' : item.id, 'name' : name});
                    });
                    this.breeder_litter = tempRabbits;
                });
            },
            showRequestForm: function(task){
                this.activeTask = task;
                $('#delete_task').modal('show');
            }
        },
        ready: function () {

        }
    };


})();
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
            },
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
(function () {

    App.Components.PedigreeForm = {
        template: "#pedigree-form-template",
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
                warnings: {}
            }
        },
        props: ['breeder','breeders'],
        components: {
            'image-upload': App.Components.ImageUpload
        },
        computed: {
            action: function () {
                if (this.breeder.id) {
                    return '/admin/pedigrees/' + this.breeder.id;
                } else {
                    return '/admin/pedigrees';
                }
            }
        },
        watch: {
            breeder: function () {
                $('.js_icheck-breeder-blue, .js_icheck-breeder-red').iCheck('update');
            }
        },
        methods: {
            initModal: function () {
                //App.initDatePicker();
                this.$http.get('/admin/breeders/getList', function (breeders) {
                    this.bucks = breeders.bucks;
                    this.does = breeders.does;
                });
            },

            sendBreeder: function () {
                var breeder = this.breeder;

                if(breeder.id != 0) {
                    breeder._method = "PUT";
                }

                this.$http.post(this.action, breeder, function (breederResponse) {
                    $('#pedigree-form').modal('hide');
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
                }).error(function (errors) {
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
                    console.log(kits);
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
(function () {

    App.Components.KitForm = {
        template: "#kit-form-template",
        data: function () {
            return {}
        },
        props: ['kit', 'litter'],
        watch: {
            kit: function () {
                $('.js_icheck-kit-blue, .js_icheck-kit-red').iCheck('update');
            }
        },
        components: {
            'image-upload': App.Components.ImageUpload
        },
        computed: {
            kit_weight: function () {
                return _.map(this.kit.weight, function (val, i) {
                    return {id: i, value: val};
                }.bind(this));
            }
        },
        methods: {
            kitDied: function (kit) {
                this.$http.get('admin/kits/' + kit.id + '/died', function () {
                    this.$dispatch('refresh-kits', kit);
                });
                $('#kit-form-modal').modal('hide');
            },

            makeBreeder: function (kit) {
                kit._method = "PUT";
                kit.weight_changed = 1;
                this.$http.post('admin/kits/' + kit.id, kit, function () {

                    this.$http.get('admin/kits/' + kit.id + '/makeBreeder', function (res) {
                        $('#kit-form-modal').modal('hide');
                        this.$router.go({path: '/profile/' + res.id});
                    });

                });
            },

            archive: function (kit) {
                this.$http.post('admin/kits/' + kit.id + '/archive', {archived: 1}, function () {
                    this.$dispatch('refresh-kits', kit);
                });
                $('#kit-form-modal').modal('hide');
            },

            unarchive: function (kit) {
                this.$http.post('admin/kits/' + kit.id + '/archive', {archived: 0}, function () {
                    this.$dispatch('refresh-kits', kit);
                });
                $('#kit-form-modal').modal('hide');
            },

            kitDelete: function (kit) {
                this.$http.post('admin/kits/' + kit.id, {_method: "DELETE"}, function () {
                    this.$dispatch('refresh-kits', kit);
                });
                $('#kit-form-modal').modal('hide');
            },

            saveKit: function (_kit) {
                var kit = _.extend({}, _kit);
                kit._method = "PUT";
                kit.weight_changed = 1;


                if (kit.new_weight) {
                    if (kit.weight == null)
                        kit.weight = [];
                    kit.weight.push(kit.new_weight);
                }

                this.$http.post('admin/kits/' + kit.id, kit, function () {
                    this.$dispatch('refresh-kits', kit);
                });
                $('#kit-form-modal').modal('hide');
            },

            father: function () {
                return _.find(this.litter.parents, function (item) {
                    return item.sex == "buck"
                });
            },
            mother: function () {
                return _.find(this.litter.parents, function (item) {
                    return item.sex == "doe"
                });
            },

        },
        ready: function () {
            $('.js_icheck-kit-blue').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue'
            }).on('ifChecked', function (event) {
                this.kit.sex = "buck";
            }.bind(this));

            //Red color scheme for iCheck
            $('.js_icheck-kit-red').iCheck({
                checkboxClass: 'icheckbox_square-red',
                radioClass: 'iradio_square-red'
            }).on('ifChecked', function (event) {
                this.kit.sex = "doe";
            }.bind(this));
        }

    };

})();
(function () {

    App.Components.Users = {
        template: "#users-template",
        data: function () {
            return {
                test: ['asd'],
                users:[],
                usersTotal: 0,
                breedersTotal: 0,
                littersTotal: 0,
                kitsTotal: 0,
                pages: 1,
            }
        },
        props: [],
        computed: {
            page: function () {
                return this.$route.query.page || 1;
            },
            currentRoute: function () {
                return this.$route.path.split('?')[0];
            },
        },
        watch: {
            page: function () {
                return this.$route.query.page || 1;
            },
            currentRoute: function () {
                return this.$route.path.split('?')[0];
            },
            page: function () {
                this.updateList();
            },
        },

        methods: {
            confirmDelete: function (user) {
                $('#delete').modal('show');
                this.toDelete = user;
            },
            deleteUser: function () {
                var user = this.toDelete;
                user._method = 'DELETE';
                this.$http.post('/admin/users/' + user.id, user)
                    .then(function () {
                        this.users = _.without(this.users, _.findWhere(this.users, {id: user.id}));
                    })
                    .error(function (data) {

                });
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

            updateList: function () {
                var data = { page: this.page};
                this.$http.get('/admin/users?paginated=1', data, function (users) {
                    this.users = users.data;
                    this.usersTotal = users.total;
                    this.pages = users.last_page;
                });
            }
        },
        ready: function () {
            this.updateList();
            this.$http.post('/admin/users/dashboard', function (data) {
                this.breedersTotal = data.breedersTotal;
                this.littersTotal = data.littersTotal;
                this.kitsTotal = data.kitsTotal;
            });
        },

    };

})();
(function () {

    App.Components.User = {
        template: "#user-template",
        data: function () {
            return {
                userRoles: [],
                errors: [],
                editMode: false,
                user: {
                    "_method": "POST",
                    "image": {
                        "name": '',
                        "path": '',
                        "temp": true,
                        "oldImage": '',
                        "delete": false,
                    },
                }
            }
        },
        computed: {
            formPath: function () {
                if (!this.editMode)
                    return '/admin/users';
                else
                    return '/admin/users/' + this.user.id;
            },
        },
        events: {
            dataHere: function () {
                this.$nextTick(function () {
                    this.initUploader();

                });
            }
        },
        methods: {
            deleteImage: function () {
                this.user.image.path = '';
                this.user.image.delete = true;
            },
            sendUser: function () {
                this.errors = [];
                if (this.editMode)
                    this.user._method = "PUT";
                //this.user.image = this.images;
                this.$http.post(this.formPath, this.user, function () {
                    this.$route.router.go({path: '/users'});
                }).error(function (data) {
                    if (data) {
                        this.errors = data;
                    }
                });
            },
            initUploader: function () {
                var self = this;
                $(self.$els['image']).fileupload({
                    dataType: 'json',
                    paramName: 'image',
                    formData: {
                        "_token": $('meta[name="csrf-token"]').attr('content'),
                        "_method": "POST",
                        "user_id": self.user.id
                    },
                    url: '/admin/images/uploadImage',
                    done: function (e, data) {
                        self.user.image.name = data.result.image.name;
                        self.user.image.path = data.result.image.path;
                        self.user.image.temp = data.result.image.temp;
                    }
                });
            }
        },
        ready: function () {
            this.$http.get('/admin/roles/getList', function (rolesList) {
                this.userRoles = rolesList;
            });
            if (this.$route.params.userId) {
                this.editMode = true;
                this.$http.get('/admin/users/' + this.$route.params.userId, function (user) {
                    this.user = user;
                });
            }
            this.initUploader();
        }
    };

})();
(function () {


    App.Components.ScheduleCalendar = {
        template: "#schedule-calendar-template",
        data: function () {
            return {
                activeTasks: [],
                activeTask: {},
                breeders: [],
                litters: [],
                generals:[],
                calendar_events: [],
                week: [],
                activeTasksForOneRabbit : [],
                activeRabbit: {},
                errors: { name: null, date: null, icon: null},
                type_of_task: 'breeder',
                perPage: 12,
                more_task_modal_title: '',
                scrollableElementId: "#schedule-calendar-scroll-box",
                scrollableElement: null,
                skipBreeders: 0,
                skipLitters: 'all',
                firstLoadAllTasks: true
            }
        },
        props: ['externalTasks'],
        computed: {},
        watch:{},
        components: {},
        events: {
            'schedule-calendar-filtering': function(msg){
                this.loadCalendarEvents(msg);
            },
            'schedule-calendar-all-task-of-current-week': function(msg){
                this.showTasksForCurrentWeek(msg);
            },
            'type-events-was-changed': function(msg){
                //msg-format :{typeOfTask: VALUE, page: PAGE_NUMBER}
                //typeOfTask: [ALL|GENERAL|BREEDER|LITTER]
                //PAGE_NUMBER: [0->oo];
                this.calendar_events = [];
                this.type_of_task = msg.typeOfTask;
                this.$emit('schedule-calendar-filtering', msg.typeOfTask);
            },
            'schedule-calendar-more-tasks-show': function(msg){
                this.more_task_modal_title = msg;
                $('#more_tasks').modal('show');
            },
            'schedule-calendar-more-tasks-hide': function(msg){
                $('#more_tasks').modal('hide');
            },
            'schedule-calendar-download-was-done':function(msg){},
            'schedule-calendar-reload':function(msg){
                this.$emit('schedule-calendar-filtering', msg.typeOfTask);
            },
            'schedule-calendar-lazy-load-tasks': function(msg){
                var self = this;
                this.loadGeneralTasks(self.firstLoadAllTasks, function(){
                    self.lazyLoadTasks();
                    self.firstLoadAllTasks = false;
                });
            }

        },
        methods: {
            getDateLeft: function(date, days) {
                var dateCopy = new Date(date);
                dateCopy.setDate(date.getDate() + days);
                return dateCopy;
            },
            addRecurringTasks: function(recurringTasks, item){
                var recurringEveryWeekId  = 2; // need add 7 times
                var recurring2WeekId      = 3; // need add 4 times
                var recurringEveryMonthId = 4; // need add 2 times

                var calendarDays          = 49;// for 7 weeks
                var currentDate           = new Date(item.date);
                var lastDay               = this.getDateLeft(currentDate, calendarDays);

                if(!recurringTasks[App.formatDate(currentDate)]) recurringTasks[App.formatDate(currentDate)] = [];
                recurringTasks[App.formatDate(currentDate)].push(item);

                if(item.recurring == recurringEveryWeekId){
                    while(currentDate <= lastDay){
                        currentDate.setDate(currentDate.getDate() + 7);

                        var itemNew = $.extend({}, item);
                        itemNew.date = App.formatDate(currentDate);

                        if(!recurringTasks[App.formatDate(currentDate)]) recurringTasks[App.formatDate(currentDate)] = [];
                        recurringTasks[App.formatDate(currentDate)].push(itemNew);

                    };
                }

                if(item.recurring == recurring2WeekId){
                    while(currentDate <= lastDay){
                        currentDate.setDate(currentDate.getDate() + 14);
                        var itemNew = $.extend({}, item);
                        itemNew.date = App.formatDate(currentDate);

                        if(!recurringTasks[App.formatDate(currentDate)]) recurringTasks[App.formatDate(currentDate)] = [];
                        recurringTasks[App.formatDate(currentDate)].push(itemNew);


                    };
                }

                if(item.recurring == recurringEveryMonthId){
                    while(currentDate <= lastDay) {
                        currentDate.setMonth(currentDate.getMonth() + 1);

                        var itemNew = $.extend({}, item);
                        itemNew.date = App.formatDate(currentDate);

                        if (!recurringTasks[App.formatDate(currentDate)]) recurringTasks[App.formatDate(currentDate)] = [];
                        recurringTasks[App.formatDate(currentDate)].push(itemNew);

                    }
                }


            },
            showAllTasksForRabbit: function (rabbit, type) {
                this.activeRabbit = rabbit;
                var ntype = (type != 'general') ? type : 'users';
                var typeId = this.activeRabbit.id ? '/' + this.activeRabbit.id : '';
                var request = '/admin/'+ ntype + typeId +'/events';
                var titleForModal = '';
                var self = this;
                var recurringTasks = {};

                var recurringOnceId       = 1;

                this.$http.get(request, {}, function (data) {
                    var recurringTasks = {};
                    var currentDate = new Date();

                    data.forEach(function(item, i, arr){
                        var itemDate = new Date(item);
                        if(item.recurring > recurringOnceId){
                            self.addRecurringTasks(recurringTasks, item);
                        }
                    });

                    if(type == 'general'){
                        var temp = [];
                        data.forEach(function(item, i, arr){if(item.type == 'general') temp.push(item);});
                        this.activeTasks = temp;
                        titleForModal = 'All General Tasks.';
                    } else {
                        this.activeTasks = [];
                        data.forEach(function(item, i, arr){
                            self.activeTasks.push($.extend({}, item));

                            $.each(recurringTasks, function(recurringTaskDate, i, arr){
                                if(recurringTaskDate == item.date){
                                    recurringTasks[recurringTaskDate].forEach(function(recurringItem, i, arr){
                                        if(recurringTaskDate != item.date){
                                            self.activeTasks.push($.extend({}, recurringItem))
                                        }
                                    });

                                    delete recurringTasks[recurringTaskDate];
                                }
                            });
                        });

                        $.each(recurringTasks, function(recurringTaskDate, i, arr){
                            recurringTasks[recurringTaskDate].forEach(function(item, i, arr){
                               self.activeTasks.push($.extend({}, item));
                            });
                        });

                        if(ntype == 'litters') titleForModal = 'All Tasks for Litter ' + this.activeRabbit.given_id;
                        else titleForModal = 'All Tasks for Breeder ' + this.activeRabbit.name;
                    }
                    this.$emit('schedule-calendar-more-tasks-show', titleForModal);
                });
            },
            showTasksForCurrentWeek: function(msg){
                this.activeRabbit = msg.rabbit;
                var weekStart   = msg.events.start_date;
                var type        = msg.type != 'general' ? msg.type : 'users';
                var request = '/admin/'+type+'/'+this.activeRabbit.id+'/events?weekStart='+weekStart;

                if(type == 'users'){
                    request = '/admin/'+type+'/events?weekStart='+weekStart;
                }

                this.$http.get(request, {}, function (data) {
                    this.activeTasks = data;
                    this.$emit('schedule-calendar-download-was-done');
                });

                this.$emit('schedule-calendar-more-tasks-show');
            },
            parseWeeklyEvents: function(events, arrayData){
                var eventsForDates = [];
                arrayData.forEach(function(itemData, dateIndex, arr){
                    eventsForDates[dateIndex] = [];
                    events.forEach(function(itemEvent, i, arr){
                        if(itemData.date === itemEvent['date']){
                            eventsForDates[dateIndex].push(itemEvent);
                        }
                    });
                });
                return eventsForDates;
            },
            loadBreederTasks: function(){
                var request = "/admin/events?for=breeder";
                this.$http.get(request, {perPage: this.perPage}, function (data) {
                    var temp = [];
                    data['data'].forEach(function(item, i, arr){
                        item.type = 'breeders';
                        temp.push(item);
                    });
                    this.calendar_events = temp;
                    this.$emit('schedule-calendar-download-was-done');
                });
            },
            loadGeneralTasks: function(attachGeneralTasks, callbackFunction){
                var request = "/admin/events?for=user";
                this.$http.get(request, {perPage: this.perPage}, function (data) {
                    if(attachGeneralTasks){
                        var general_events = [];
                        var dataIsEmpty = false;

                        data.forEach(function(item, i, arr){
                            if(item.count) dataIsEmpty = true;
                        });

                        if(dataIsEmpty) general_events.push({events: data, type : 'general'});

                        this.calendar_events = general_events;
                    }

                    if(callbackFunction) callbackFunction();

                    this.$emit('schedule-calendar-download-was-done');
                });
            },
            loadLitterTasks: function(){
                var request = "/admin/events?for=litter&weekStart=" + App.formatDate(this.getFirstDayOfCurrentWeek());
                this.$http.get(request, {perPage: this.perPage}, function (data) {
                    var temp = [];
                    data['data'].forEach(function(item, i, arr){
                        item.type = 'litters';
                        temp.push(item);
                    });
                    this.calendar_events = temp;
                });
            },
            loadCalendarEvents: function(type){
                switch(type){
                    case 'general': {
                        this.loadGeneralTasks(true); break;}
                    case 'litter':  {
                        this.loadLitterTasks();  break;}
                    case 'breeder': {
                        this.loadBreederTasks(); break;}
                    case 'all':{}
                    default: {
                        this.skipBreeders = 0;
                        this.firstLoadAllTasks = true;
                        this.skipLitters = 'all';
                        this.$emit('schedule-calendar-lazy-load-tasks', {cleanCalendar: true});
                    };
                }
            },
            openTaskForm: function(events, rabbit, type){
                if(events.count == 1){
                    if(!events.event.breed_id){
                        this.$dispatch('task-managing', events.event);
                    }
                } else if( !events.count){
                    this.$dispatch('task-managing', events);
                } else {
                    this.$dispatch('schedule-calendar-all-task-of-current-week', {rabbit: rabbit, events: events, type: type});
                }
            },
            makeTimelineDate: function(date){
                return moment(date).format('MM/DD/YY');
            },
            getFirstDayOfCurrentWeek: function(){
                var dayForTimTable = 7;
                var today = new Date();
                var currentYear = today.getFullYear();
                var newYear = new Date(currentYear, 0, 1);
                var newYearDay = newYear.getDay();
                var delta = Math.floor((today.getTime() - newYear.getTime())/1000/60/60/24);
                var wNum = Math.floor((delta + newYearDay)/7) - 1;
                var n = wNum;
                var w = 1;
                var firstDay = newYear.getDay();
                var days = firstDay == w ? 1 : (n + 1) * 7 + w - (firstDay - 1);
                var mondayOfCurrentWeek = new Date(currentYear, 0, days);
                return mondayOfCurrentWeek; //return date of Monday for current week.
            },
            makeCalendarLabels: function(){
                this.week = [];
                var dayForTimTable = 7;
                var mondayOfCurrentWeek = this.getFirstDayOfCurrentWeek();
                today = App.formatDate(new Date());
                this.week.push({date: App.formatDate(mondayOfCurrentWeek)});

                for(var i=1; i < dayForTimTable; i++){
                    mondayOfCurrentWeek.setDate(mondayOfCurrentWeek.getDate()+7);
                    var newData = App.formatDate(mondayOfCurrentWeek);
                    var comment = null;
                    if(today == newData) comment = 'Today';

                    this.week.push({date: newData, comment: comment});
                }
            },
            showRequestForm: function(task){
                this.$dispatch('events-for-deleting', task);
                this.$emit('schedule-calendar-more-tasks-hide', task);
            },
            lazyLoadTasks: function(){
                var request = "/admin/events";
                var self = this;

                this.$http.get(request, {'for':'all', 'perPage':this.perPage, 'skipBreeders': this.skipBreeders, 'skipLitters': this.skipLitters}, function (data) {
                    if(data['breeders']){
                        var countOfRabbits = 0;
                        var fetchedAll = false;

                        data['breeders'].forEach(function(item, i, arr){
                            if(item.events){
                                item['type'] = 'breeders';
                                self.calendar_events.push(item);
                                countOfRabbits++;
                            } else if(item == 'fetchedAll'){
                                fetchedAll = true;
                            }
                        });

                        this.skipBreeders = (fetchedAll) ? 'all' : countOfRabbits;

                        if(this.skipBreeders == 'all') {
                            this.skipLitters = 0;
                            this.$emit('schedule-calendar-lazy-load-tasks', {});
                        }
                    };

                    if(data['litters']){
                        countOfRabbits = 0;
                        var fetchedAll = false;
                        data['litters'].forEach(function(item, i, arr){
                            if(item.events){
                                item['type'] = 'litters';
                                self.calendar_events.push(item);
                            } else if(item == 'fetchedAll'){
                                fetchedAll = true;
                            }
                        });
                        this.skipLitters = (fetchedAll) ? 'all' : countOfRabbits;
                        if(this.skipLitters == 'all') this.skipBreeders = 'all';
                    }
                });
            },
            scrollHandlerLazyLoad: function(event){
                var newScrollLeft = this.scrollableElement.scrollLeft();
                var width         = this.scrollableElement.width();
                var scrollWidth   = this.scrollableElement.get(0).scrollWidth;

                if ((scrollWidth - newScrollLeft - width) <= 1) {
                    this.$emit('schedule-calendar-lazy-load-tasks', {});
                }
            },

            eventTitle: function(event){
                if(event.name && event.date){
                    return event.name +': '+ event.date
                }
                return "View All";
            }
        },
        ready: function () {
            this.makeCalendarLabels();
            this.scrollableElement = $(this.scrollableElementId);
            this.$dispatch('schedule-calendar-filtering', 'all');
        },
    };


})();
(function () {


    App.Components.UpcommingTasks = {
        template: "#upcomming-tasks-template",
        data: function () {
            return {
                activeTasks: [],
                activeTask: {},
                originalTask:{},
                rabbits: [],
                breeder_litter: [],
                week: [],
                label : 'label',
                label_color : 'label-danger',
                type_of_task : 'all',
                current_page : null,
                last_page : null,
                perPage : 10,
                pagination_page: [],
                activeTasksForOneRabbit : [],
                activeRabbit: {},
                info_panel_class : ' bg-info',
                button_class : ' btn-primary',
                name_of_modal: 'New Task',
                modeOfTask: 'create',
                errors: { name: null, date: null, icon: null},
                type_of_tasks: 'all',
                iconBackground: {'bg-red': false, 'bg-blue': false, 'bg-maroon': false, 'bg-green': false, 'bg-yellow': false, 'bg-grey': false, 'fa-calendar' : false, 'fa-heart' : false, 'fa-asterisk' : false, 'fa-bookmark' : false, 'fa-eye' : false, 'fa-flag' : false, 'fa-medkit' : false, 'fa-paw' : false, 'fa-trophy' : false}
            }
        },
        props: ['externalTasks'],
        computed: {},
        watch:{
            'activeTask.type': function(newValue, oldValue){
                switch(newValue){
                    case 'breeder': {this.loadBreeders(); break;}
                    case 'litter': {this.loadLitters(); break;}
                    default:{this.breeder_litter = [];}
                }
            },
            'externalTasks': function (newValue, oldValue) {
                this.activeTasks = newValue;
            },
            'activeTask.icon': function(newValue, oldValue){
                if(newValue){
                    var newClass = newValue.split(' ')[1];
                    var oldClass = oldValue ? oldValue.split(' ')[1] : '';

                    if(newClass == 'bg-gray') newClass = newValue.split(' ')[0];
                    if(oldClass == 'bg-gray') oldClass = oldValue ? oldValue.split(' ')[0] : '';

                    this.iconBackground[newClass] = true;
                    if(oldValue) this.iconBackground[oldClass] = false;
                }
            },
        },
        events: {
            'upcoming-tasks-managing': function (task) {
                this.openTaskForm(task);
            },
            'upcomming-tasks-reload': function(msg){
                this.loadTasks(msg.typeOfTask, msg.page);
            },
            'type-events-was-changed': function(msg){
                this.type_of_task = msg.typeOfTask;
                this.$dispatch('upcomming-tasks-reload', msg)

            },
            'upcomming-tasks-delete-task': function(msg){
                this.showRequestForm(msg);
            },
        },
        methods: {
            capitalize : function (string) {
                return string.replace(/^./, function (match) {
                    return match.toUpperCase();
                });
            },
            updateSettings: function () {},
            getTypeId: function(type){
                var types = {'general' : 1, 'litter' : '2','breeder' : 3};
                return types[type];
            },
            openTaskForm: function(task){
                if(task){
                    this.info_panel_class = "bg-info";
                    this.name_of_modal = "Edit Task";
                    this.button_class = "btn-primary";
                    this.modeOfTask = 'edit';
                    this.$http.get('/admin/events/'+task.id, {}, function (task) {
                        this.activeTask = $.extend({}, task);
                        this.originalTask = $.extend({}, task);
                        this.activeTask.relation = {};
                        if(this.activeTask.litters || this.activeTask.breeders){
                            this.activeTask.relation = this.activeTask.litters ? this.activeTask.litters[0] : this.activeTask.breeders[0];
                        }
                    });

                } else {
                    this.modeOfTask = 'create';
                    this.name_of_modal = "Create Task";
                    this.info_panel_class = "bg-success";
                    this.button_class = "btn-success";
                    this.errors = { name: null, date: null, icon: null};
                    this.activeTask = {'name': null,'date': App.formatDate(new Date), 'recurring': "1", 'type': "general", 'icon' : 'fa-cutlery bg-red', relation: {id: ''}};
                }

                $('#new_task').modal('show');
                $('#more_tasks').modal('hide');
            },
            createTask: function(mode){
                var self = this;
                if(this.$activeTaskValidator.touched && this.$activeTaskValidator.invalid) return;

                if(mode == 'edit'){
                    this.activeTask.type_id = this.activeTask.relation.id || null;
                    this.activeTask.type_changed = (this.originalTask.type !== this.activeTask.type) || (this.originalTask.type_id !== this.activeTask.type_id);
                    this.$http.put('/admin/events/'+ this.activeTask.id, this.activeTask, function(data){
                        if(self.activeTask.closed == '0'){
                            self.$http.get('/admin/events/' + self.activeTask.id + '/reopen', function(data){});
                        } else {
                            self.$http.get('/admin/events/' + self.activeTask.id + '/close', function(data){});
                        }
                        this.$dispatch('reload-tasks', {typeOfTask: this.type_of_task, page: this.page});
                    });
                } else {
                    this.activeTask._method = "POST";
                    this.activeTask.type_id = this.activeTask.relation ? this.activeTask.relation.id : null;
                    this.$http.post('/admin/events', this.activeTask, function(data){
                        this.$dispatch('reload-tasks', {typeOfTask: this.type_of_task, page: this.page});
                    });
                }

                $('#new_task').modal('hide');
            },
            finishTask: function(task){
                if(task.closed == '1'){
                    this.$http.get('/admin/events/' + task.id + '/reopen', function(data){
                        task.closed = '0';
                    });
                } else {
                    this.$http.get('/admin/events/' + task.id + '/close', function(data){
                        task.closed = '1';
                    });
                }
            },
            loadTasks: function (typeOfTask, page) {
                var filter;

                if(!typeOfTask){typeOfTask = this.type_of_task;}
                if(typeOfTask == 'expired'){
                    filter = 'type=general,litter,breeder&expired=true'
                } else {
                    switch(typeOfTask){
                        case "general" : {filter='type=general';break;}
                        case "breeder" : {filter='type=breeder';break;}
                        case "litter"  : {filter='type=litter';break;}
                        default        : {filter = 'type=general,litter,breeder';}
                    }

                    //filter += "&sinceToday=true";
                }

                var request = '/admin/events?' + filter;

                this.$http.get(request, {'perPage': this.perPage, page: page}, function (tasks) {
                    this.activeTasks = tasks['data'];
                    this.current_page = tasks['current_page'];
                    this.last_page = tasks['last_page'];
                    this.makeTaskPaginationData(this.last_page);
                }, function(error){
                });
            },
            getFullTaskName: function(task){
                if(task.holderName){
                    if(task.type == 'breeder'){
                        return task.holderName + ': ' + task.name;
                    }
                    if(task.type == 'litter'){
                        return 'Litter ' + task.holderName + ': ' + task.name;
                    }
                }
                return task.name;
            },
            deleteTask : function(task){
                if(task && task.id){
                    this.$http.post('/admin/events/' + task.id, { _method: "DELETE" }, function (res) {
                        var msg = {};
                        $('#id_task_'+task.id).slideUp(200);
                        $("#delete_task").modal('hide');
                        this.activeTask = {};

                        msg.typeOfTask = this.type_of_task;
                        this.$dispatch('reload-tasks', msg);
                    });
                } else {
                    var tasksForDelete = [];
                    this.activeTasks.forEach(function(item, i, arr){
                        if(item.closed == 1) tasksForDelete.push(item.id);
                    });

                    if(tasksForDelete.length){
                        this.$http.post('/admin/events/deleteEvents', {events: tasksForDelete}, function(data){
                            var msg = {typeOfTask: this.type_of_task};
                            $("#delete_task").modal('hide');
                            this.$dispatch('reload-tasks', msg);
                        });
                    } else {
                        var msg = {typeOfTask: this.type_of_task};
                        $("#delete_task").modal('hide');
                        this.$dispatch('reload-tasks', msg);
                    }
                }
            },
            loadBreeders: function(){
                this.$http.get('/admin/breeders/getList', {}, function (breeders) {
                    this.breeder_litter = breeders.bucks.concat(breeders.does);
                });
            },
            loadLitters: function(){
                this.$http.get('/admin/litters/getList', {}, function (litters) {
                    var tempRabbits = [];
                    litters.forEach(function(item, index, arr){
                        var name = item.parents.map(function(elem){
                            return elem.name;
                        }).join("/");
                        tempRabbits.push({'given_id' : item.given_id, 'id' : item.id, 'name' : name});
                    });
                    this.breeder_litter = tempRabbits;
                });
            },
            getTimeLeft: function(task){
                var oToday = moment().startOf("day").utcOffset(0);
                var oDeadLineDate = new moment(task.date);
                var nDaysLeft = oDeadLineDate.diff(oToday, "days");
                if(!nDaysLeft) return 'today';
                var text = Math.abs(nDaysLeft) + ' days';
                if(Math.abs(nDaysLeft) == 1) text = Math.abs(nDaysLeft) + ' day';
                if(nDaysLeft < 0) {
                    //text = text + " ago";
                    text = "expired";
                }
                return text;
            },
            getColorForDate: function(task){
                var labelClass = "label ";
                //start time : from today
                //within 1 days:red.
                var dangerDay = 1; // label-danger
                //within 2-7 days : yellow.
                var warningDay = 7; //label-warning
                //1-2 weeks : primary blue,
                //var infoDay = 14; //label-info
                //3-4 weeks : blue,
                //var primaryDay = 28; //label-primary
                //all beyond : green. label-success

                var oToday = new Date();
                var oDeadLineDate = new Date(task.date);
                var nDaysLeft = oDeadLineDate > oToday ? Math.ceil((oDeadLineDate - oToday) / (1000 * 60 * 60 * 24)) : null;

                if(nDaysLeft <= dangerDay){
                    return labelClass + "label-danger";
                } else if(nDaysLeft <= warningDay){
                    return labelClass + "label-warning";
                } else {
                    return false;
                }
            },
            makeTaskPaginationData: function(last_page){
                if(last_page === 1){
                    this.pagination_page =[];
                    return;
                }
                this.pagination_page =[];
                for(var i=1; i <= last_page; i++){
                    this.pagination_page.push(i);
                }
            },
            showRequestForm: function(task){
                this.activeTask = task;

                $('#delete_task').modal('show');
            }
        },
        ready: function () {
            this.loadTasks();
            FastClick.attach(document.getElementById('todo-list-fastclick'));
        }
    };


})();
(function () {


    App.Components.Schedule = {
        template: "#schedule-template",
        data: function () {
            return {
                activeTasks: [],
                perPage: 10,
                litters: [],
                plans: [],
                activeBirth: {}
        }
        },
        props: [],
        computed: {},
        components: {
            'upcomming-tasks': App.Components.UpcommingTasks,
            'schedule-calendar': App.Components.ScheduleCalendar,
            'notification-tab': App.Components.NotificationTab,
            'litter-weight': App.Components.LitterWeight,
            'litter-butcher': App.Components.LitterButcher
        },
        watch:{},
        events:{
            'task-managing': function(msg){
                this.$broadcast('upcoming-tasks-managing',msg);
            },
            'schedule-calendar-reload': function(msg){
                this.$broadcast('schedule-calendar-filtering', msg);
            },
            'reload-tasks': function(msg){
                this.$broadcast('upcomming-tasks-reload', msg);
                this.$broadcast('schedule-calendar-reload', msg);
            },
            'type-events-was-changed':function(msg){
                this.$broadcast('type-events-was-changed', msg);
            },
            'events-for-deleting':function(msg){
                this.$broadcast('upcomming-tasks-delete-task', msg);
            }
        },
        methods: {
            changeTypeForFilter: function(typeOfTask, page){
                this.$dispatch('type-events-was-changed', {typeOfTask: typeOfTask, page: page});
            },
            lostLitters: function () {
                var data = { page: this.page };
                data.archived = this.filter == "archive" ? 1 : 0;

                this.$http.get('/admin/litters', data, function (litters) {
                    this.loading = 0;
                    this.litters = litters.data;
                    this.pages = litters.last_page;
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


        },
        ready: function () {
            this.lostLitters();
            this.birthModal();
        },
    };


})();
(function () {

    App.Components.Dashboard = {
        template: "#dashboard-template",
        data: function () {
            return {
                litter: {},
                litters: [],
                kits: [],
                breeders: [],
                activeBreed: {},
                activeBirth: {},
                plans: [],
                dummyEvents: []
            }
        },
        props: [],
        computed: {

        },
        components: {
            'litter-weight': App.Components.LitterWeight,
            'litter-butcher': App.Components.LitterButcher,
            'upcomming-tasks': App.Components.UpcommingTasks,
            'schedule-calendar': App.Components.ScheduleCalendar,
        },
        events: {
            'reload-tasks': function(msg){
                this.$broadcast('upcomming-tasks-reload', msg);
                this.$broadcast('schedule-calendar-reload', msg);
            },
            'task-managing': function(msg){
                this.$broadcast('upcoming-tasks-managing',msg);
            },

        },
        watch: {
            'activeBreed.date': function(newValue, oldValue){
                if(newValue){
                    this.generateDummyEvents(newValue);
                }
            }
        },
        methods: {
            newLitter: function () {
                this.$route.router.go({
                    name: 'litters',
                    params: { action: 'new' }
                });
            },

            newBreeder: function () {
                this.$route.router.go({
                    name: 'breeders',
                    params: { action: 'new' }
                });
            },

            breedModal: function () {
                this.loadBreeders();
                this.activeBreed = {doe: '-1', buck:"-1", date: moment(new Date()).format(App.dateFormat)};
                var today = moment(new Date()).format(App.dateFormat);
                this.generateDummyEvents(today);
                $('#breed').modal('show');
            },
            generateDummyEvents: function(date) {
                this.$http.get('/admin/events/breedPlanDummyEvents', {date: date}, function (events) {
                    this.dummyEvents = events;
                });
            },
            createBreed: function(breed){
                this.$http.post('/admin/events/makeBreedPlan', breed, function (res) {
                    $('#breed').modal('hide');
                    this.$dispatch('reload-tasks', {typeOfTask: 'all', page: null});
                });
            },
            birthModal: function () {
                this.$http.get('/admin/users/plans', {}, function (plans) {
                    this.plans = plans;
                    $('#birth').modal('show');
                });

                this.activeBirth = {breedplan: '-1', born: moment(new Date()).format(App.dateFormat)};
            },

            recordBirth: function () {
                this.$http.post('/admin/litters', this.activeBirth, function (res) {
                    $('#birth').modal('hide');
                });
            },

            weightModal: function () {
                $('#litter-weight-modal').modal('show');
            },

            butcherModal: function () {
                $('#litter-butcher-modal').modal('show');
            },
            loadBreeders: function(){
                this.$http.get('/admin/breeders/getList', {}, function (breeders) {
                    this.breeders = breeders;
                });
            },

        },
        ready: function () {
            this.$http.get('admin/litters', { page: 1, archived: false }, function (res) {
                this.litters = res.litters.data;
            });
        },

    };

})();
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
(function () {

    App.Components.LitterProfile = {
        template: "#litter-profile-template",
        data: function () {
            return {
                litter: {
                    id: this.$route.params.id,
                    parents: []
                },
                litters: [],
                activeLitter: {},
                activeKit: {},
                activeKits: [],
                litterLoad: false,
                aliveKitsAmount: null,
                activeTimeline: [],
                plans: [],
                activeBirth: {}
            }
        },
        props: [],
        computed: {
            confirmTarget: function () {
                return "this litter";
            },
            aliveKits: function () {
                return this.aliveKitsAmount != null ? this.aliveKitsAmount : ""; /*litter.kits_amount - litter.kits_died*/
            }
        },
        components: {
            'kit-form': App.Components.KitForm,
            'litter-box': App.Components.LitterBox,
            'litter-form': App.Components.LitterForm,
            'litter-weight': App.Components.LitterWeight,
            'litter-butcher': App.Components.LitterButcher,
            'task-form' : App.Components.TaskForm
        },
        events: {
            'reload-tasks': function(msg){
                this.loadTasks('litters');
            },

            'litter-updated': function(msg){
                this.$broadcast('refresh-kits-global', this.litter.id);
            },
        },
        methods: {
            loadLitter: function () {
                this.$http.get('/admin/litters/'+this.litter.id, function (res) {
                    this.litter = res;
                    this.litterLoad = true;
                });
            },
            loadAllLitters: function () {
                this.$http.get('/admin/litters', function (res) {
                    this.litters = res.data;
                });
            },
            updateSettings: function () {

            },
            editKit: function (kit) {
                this.activeKit = kit;
                $('#kit-form-modal').modal('show');
            },
            refreshKits: function () {
                this.loadLitter();
                this.$broadcast('refresh-kits-global', this.litter.id);
            },

            father: function () {
                return _.find(this.litter.parents, function (item) { return item.sex == "buck" });
            },
            mother: function () {
                return _.find(this.litter.parents, function (item) { return item.sex == "doe" });
            },

            updateAlive: function (data) {
                this.aliveKitsAmount = data;
            },

            editModal: function () {
                $('#litter-form').modal('show');
            },

            weightModal: function () {
                this.activeLitter = _.extend({}, this.litter);
                this.$http.get('admin/litters/' + this.litter.id + '/weigh?animal_type=rabbitkit', {}, function (kits) {
                    this.activeKits = kits;
                });
                $('#litter-weight-modal').modal('show');
            },

            butcherModal: function (litter) {
                $('#litter-butcher-modal').modal('show');
            },

            archiveModal: function () {
                $('#archive-breed-modal').modal('show');
            },
            archive: function () {
                $('#archive-breed-modal').modal('hide');
                this.$http.post('/admin/litters/' + this.litter.id + '/archive', {archived: 1}, function (res) {
                    this.$router.go({ path: '/litters' });
                });
            },

            deleteModal: function () {
                $('#delete-breed-modal').modal('show');
            },

            delete: function () {
                $('#delete-breed-modal').modal('hide');
                this.$http.post('/admin/litters/' + this.litter.id, {_method: "DELETE"}, function (res) {
                    this.$router.go({ path: '/litters' });
                });
            },

            loadRabbit: function(rabbit, type){
                this.activeRabbit = rabbit;
                this.loadTasks(type);
            },

            loadTasks: function(type){
                var self = this;
                var ntype = (type != 'general') ? type : 'users';
                var typeId = this.activeRabbit.id ? '/' + this.activeRabbit.id : '';
                var request = '/admin/'+ ntype + typeId +'/events';
                this.$http.get(request, {}, function (data) {
                    self.activeTimeline = [];
                    self.activeTimeline.push(data);
                });
            },

            getTimeLeft: function(task){
                var oToday = new Date();
                var oDeadLineDate = new Date(task.date);
                var nDaysLeft = oDeadLineDate > oToday ? Math.ceil((oDeadLineDate - oToday) / (1000 * 60 * 60 * 24)) : null;
                if(!nDaysLeft) return 'Today';

                if(nDaysLeft == 1) return nDaysLeft + ' day';
                return nDaysLeft + ' days';
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

            openTaskForm: function (task) {
                this.activeTask = task;
                this.$broadcast('tasks-managing', task);
            },

            showRequestForm: function(task){
                this.activeTask = task;
                $('#delete_task').modal('show');
            },

            deleteTask : function(task){
                if(task && task.id){
                    this.$http.post('/admin/events/' + task.id, { _method: "DELETE" }, function (res) {
                        $('#id_task_'+task.id).slideUp(200);
                        $("#delete_task").modal('hide');
                        this.activeTask = {};
                        this.$dispatch('reload-tasks');
                    });
                } else {
                    var tasksForDelete = [];
                    this.activeTasks.forEach(function(item, i, arr){
                        if(item.closed == 1) tasksForDelete.push(item.id);
                    });

                    if(tasksForDelete.length){
                        this.$http.post('/admin/events/deleteEvents', {events: tasksForDelete}, function(data){
                            $("#delete_task").modal('hide');
                            this.$dispatch('reload-tasks');
                        });
                    } else {
                        $("#delete_task").modal('hide');
                    }
                }
            },
        },
        ready: function () {
            App.MobileTypes();
            this.loadLitter();
            this.loadAllLitters();
            this.birthModal();
        },

    };

})();
(function () {

    App.Components.Profile = {
        template: "#profile-template",
        data: function () {
            return {
                breeder: {
                    image: {}
                },
                breeders: [],
                bucks: [],
                does: [],
                errors: {},
                litters: [],
                activeKits: [],
                activeKit: {},
                activeLitter: {},
                activeTimeline:[],
                activeTask: {}
            }
        },
        props: [],
        components: {
            'kit-form': App.Components.KitForm,
            'litter-box': App.Components.LitterBox,
            'breeder-form': App.Components.BreederForm,
            'litter-weight': App.Components.LitterWeight,
            'litter-butcher': App.Components.LitterButcher,
            'litter-form': App.Components.LitterForm,
            'task-form' : App.Components.TaskForm
        },
        computed: {
            id: function () {
                return this.$route.params.id;
            },
            breedSex: function () {
                this.getRabbitSex(this.breeder);
            },
            breedName: function () {
                return this.breeder.name;
            },
            breedSexClass: function () {
                if(this.breeder.sex == "doe") {
                    return "box-danger";
                } else if(this.breeder.sex == "buck") {
                    return "box-info";
                }
            },
            confirmTarget: function () {
                return this.breeder.name;
            }
        },
        events: {
            'reload-tasks': function(msg){
                this.loadTasks('breeders');
            },
        },
        methods: {
            showProfile: function () {
                this.$http.get('/admin/breeders/'+this.id)
                    .then(function (result) {
                        this.breeder = result.data;
                    });
            },
            edit: function () {
                $('#breeder-form').modal('show');
                this.$http.get('/admin/breeders/getList', function (breeders) {
                    this.bucks = breeders.bucks;
                    this.does = breeders.does;
                });
            },
            getParent: function (parent) {
                return parent ? parent.name + ": " + parent.tattoo : "Unknown";
            },

            archiveModal: function (id) {
                $('#archive-breed-modal').modal('show');
            },
            archive: function () {
                $('#archive-breed-modal').modal('hide');
                this.$http.post('/admin/breeders/'+this.id+'/archive', { archived: 1 }, function (res) {
                    this.$router.go({ path: '/breeders/archive' });
                });
            },

            deleteModal: function (id) {
                $('#delete-breed-modal').modal('show');
            },
            delete: function () {
                $('#delete-breed-modal').modal('hide');
                this.$http.post('/admin/breeders/' + this.id, { _method: "DELETE" }, function (res) {
                    this.$router.go({ path: '/breeders' });
                });
            },


            loadLitters: function () {
                this.$http.get('/admin/breeders/'+this.id+'/getLitters', {}, function (response) {
                    this.litters = response.data;
                });
            },

            weightLitter: function (litter) {
                this.activeLitter = litter;
                this.$http.get('admin/litters/' + this.activeLitter.id + '/weigh?animal_type=rabbitkit', {}, function (kits) {
                    this.activeKits = kits;
                });
                $('#litter-weight-modal').modal('show');
            },

            butcherModal: function (litter) {
                this.activeLitter = litter;
                this.$http.get('admin/litters/' + this.activeLitter.id + '/weigh?animal_type=rabbitkit', {}, function (kits) {
                    this.activeKits = kits;
                });
                $('#litter-butcher-modal').modal('show');
            },

            editLitterModal: function (litter) {
                this.activeLitter = litter;
                $('#litter-form').modal('show');
            },

            archiveLitterModal: function (litter) {
                this.activelitter = litter;
                $('#archive-litter-modal').modal('show');
            },
            archiveLitter: function () {
                $('#archive-litter-modal').modal('hide');
                this.$http.post('/admin/litters/' + this.activelitter.id + '/archive', {archived: 1}, function (res) {
                    $('#id_' + this.activelitter.id).slideUp(200);
                    this.refreshKits();
                });
            },

            deleteLitterModal: function (litter) {
                this.activelitter = litter;
                $('#delete-litter-modal').modal('show');
            },
            deleteLitter: function () {
                $('#delete-litter-modal').modal('hide');
                this.$http.post('/admin/litters/' + this.activelitter.id, {_method: "DELETE"}, function (res) {
                    $('#id_' + this.activelitter.id).slideUp(200);
                    this.refreshKits();
                });
            },

            openTaskForm: function (task) {
                this.activeTask = task;
                this.$broadcast('tasks-managing', task);
            },

            showRequestForm: function(task){
                this.activeTask = task;
                $('#delete_task').modal('show');
            },

            deleteTask : function(task){
                if(task && task.id){
                    this.$http.post('/admin/events/' + task.id, { _method: "DELETE" }, function (res) {
                        $('#id_task_'+task.id).slideUp(200);
                        $("#delete_task").modal('hide');
                        this.activeTask = {};
                        this.$dispatch('reload-tasks');
                    });
                } else {
                    var tasksForDelete = [];
                    this.activeTasks.forEach(function(item, i, arr){
                        if(item.closed == 1) tasksForDelete.push(item.id);
                    });

                    if(tasksForDelete.length){
                        this.$http.post('/admin/events/deleteEvents', {events: tasksForDelete}, function(data){
                            $("#delete_task").modal('hide');
                            this.$dispatch('reload-tasks');
                        });
                    } else {
                        $("#delete_task").modal('hide');
                    }
                }
            },

            editKit: function (kit) {
                this.activeKit = kit;
                $('#kit-form-modal').modal('show');
            },
            refreshKits: function () {
                this.loadLitters();
                this.showProfile();
            },

            makeDate: function (date) {
                return moment(date).format(App.dateFormat);
            },

            loadRabbit: function(rabbit, type){
                this.activeRabbit = rabbit;
                this.loadTasks(type);
            },

            loadTasks: function(type){
                var self = this;
                var ntype = (type != 'general') ? type : 'users';
                var typeId = this.activeRabbit.id ? '/' + this.activeRabbit.id : '';
                var request = '/admin/'+ ntype + typeId +'/events';
                this.$http.get(request, {}, function (data) {
                    self.activeTimeline = [];
                    self.activeTimeline.push(data);
                });
            },

            getTimeLeft: function(task){
                var oToday = new Date();
                var oDeadLineDate = new Date(task.date);
                var nDaysLeft = oDeadLineDate > oToday ? Math.ceil((oDeadLineDate - oToday) / (1000 * 60 * 60 * 24)) : null;
                if(!nDaysLeft) return 'Today';

                if(nDaysLeft == 1) return nDaysLeft + ' day';
                return nDaysLeft + ' days';
            },

            getSecondParentSex: function(litter){
                var parent = this.getBreederPartner(litter);
                return this.getRabbitSex(parent);
            },

            getSecondParentName: function(litter){
                var parent = this.getBreederPartner(litter);
                return parent.name;
            },

            getBreederPartner: function(litter){
                var self = this;
                var parent = _.find(litter.parents, function(parent){
                    return self.breeder.id != parent.id;
                });
                return parent;
            },
            getRabbitSex: function (rabbit) {
                if(rabbit.sex == "doe") {
                    return "Doe";
                } else if(rabbit.sex == "buck") {
                    return "Buck";
                } else {
                    return "Breed";
                }
            },

        },
        ready: function () {
            this.showProfile();
            this.loadLitters();
            App.MobileTypes();

            this.$on('refresh-kits', this.refreshKits.bind(this));
        }

    };

})();
(function () {

    App.Components.Pedigree = {
        template: "#pedigree-template",
        data: function () {
            return {
                breeder: {
                    image: {}
                },
                generations: {},
            }
        },
        components: {
            'pedigree-form': App.Components.PedigreeForm,
        },
        computed: {
            id: function () {
                return this.$route.params.id;
            },
            breedSex: function () {
                if(this.breeder.sex == "doe") {
                    return "Doe";
                } else if(this.breeder.sex == "buck") {
                    return "Buck";
                } else {
                    return "Breed";
                }
            },
            breedName: function () {
                return this.breeder.name;
            },
            breedSexClass: function () {
                if(this.breeder.sex == "doe") {
                    return "box-danger";
                } else if(this.breeder.sex == "buck") {
                    return "box-info";
                }
            },
            confirmTarget: function () {
                return this.breeder.name;
            }
        },
        methods: {

            showProfile: function (id) {
                this.$http.get('/admin/pedigrees/'+id)
                    .then(function (result) {
                        this.breeder = result.data;
                    });
            },
            loadLitters: function () {
                this.$http.get('/admin/breeders/'+this.id+'/getPedigree', {}, function (response) {
                    this.generations = response;
                    //console.log(this.generations);
                });

            },

            edit: function (id) {
                this.showProfile(id);
                $('#pedigree-form').modal('show');
                //App.initDatePicker();
                /*this.$http.get('/admin/breeders/getList', function (breeders) {
                    this.bucks = breeders.bucks;
                    this.does = breeders.does;
                });*/
            },

        },
        ready: function () {
            var gthis = this;
            gthis.loadLitters();
            $('#pedigree-form').on('hidden.bs.modal', function (e) {
                // do something...
                gthis.loadLitters();
            })
            //this.showProfile();
        }

    };

})();
(function () {

    App.Components.Settings = {
        template: "#settings-template",
        data: function () {
            return {
                user: {},
                user_id: "",
                success: {},
                errors: {},
                success_general: {},
                success_pedigree: {},
                iconBackground: {'bg-red': false, 'bg-blue': false, 'bg-maroon': false, 'bg-green': false, 'bg-yellow': false, 'bg-grey': false, 'bg-purple' : false, 'fa-calendar' : false, 'fa-heart' : false, 'fa-asterisk' : false, 'fa-bookmark' : false, 'fa-eye' : false, 'fa-flag' : false, 'fa-medkit' : false, 'fa-paw' : false, 'fa-trophy' : false, 'fa-inbox' : false}
            }
        },
        props: [],
        computed: {

        },
        watch:{

            'chainIcon': function(newValue, oldValue){

                if(newValue){
                    var newClass = newValue.split(' ')[1];
                    var oldClass = oldValue ? oldValue.split(' ')[1] : '';

                    if(newClass == 'bg-gray') newClass = newValue.split(' ')[0];
                    if(oldClass == 'bg-gray') oldClass = oldValue ? oldValue.split(' ')[0] : '';

                    this.iconBackground[newClass] = true;
                    if(oldValue) this.iconBackground[oldClass] = false;

                }
            },
        },
        methods: {
            updateSettings: function (from) {
                this.success = {};
                this.errors = {};
                this.success_general = {};
                this.success_pedigree = {};


                /*Upload file*/
                var files = this.$els.avatar.files;
                var data = new FormData();
                if(files[0]){

                    data.append('avatar', files[0]);

                    this.$http.post('admin/users/'+this.user_id+'/logo', data, function (data, status, request) {
                        //handling
                        var image = $('#logo_preview').data('directory') + '/' + data.filename ;
                        $('#logo_preview').attr('src',image);
                    }).error(function (data, status, request) {
                        //handling
                        console.log(data);
                        console.log(status);
                        console.log(request);
                    });
                }
                /**/


                this.$http.post('admin/users/'+this.user_id+'/settings', this.user, function (res) {

                    if(from=='general'){
                        this.success_general = res;
                    }else if(from=='pedigree'){
                        this.success_pedigree = res;
                    }else{
                        this.success = res;
                    }

                }).error(function (errors) {
                    this.errors = errors;
                });

            },
            addChain: function () {
                if(!this.chainName) {
                    alert('You must specify Name');
                    return false;
                }
                if(!this.chainDays) {
                    alert('You must specify Days');
                    return false;
                }
                var name = this.chainName,
                    days = this.chainDays,
                    icon = this.chainIcon,
                    html = '',
                    id      = 'new_' + new Date().getTime()
                    ;
                html =
                    '<li class="'+id+'">' +
                        '<i class="fa '+icon+'"></i>'+
                        '<div class="timeline-item">'+
                        '<input type="hidden" v-model="user.breedchains.icon['+id+']" value="'+icon+'">'+
                        '<span class="time"><button type="button" class="btn btn-danger btn-xs"  onclick="App.Components.Settings.methods.removeChain(\''+id+'\')"><i class="fa fa-remove"></i></button></span>'+
                        '<span class="time"><input size="2" type="text" placeholder="0" value="'+days+'" v-model="user.breedchains.days['+id+']"> Days</span>'+
                        '<h3 class="timeline-header"><input size="16" placeholder="Breed" type="text" value="'+name+'" v-model="user.breedchains.name['+id+']"></h3>'+
                        ''+
                        ''+
                        '</div>'+
                    '</li>'
                    ;

                //Force to push into vuejs model
                //I'm sure exist another way to do this, more elegant.
                if(!this.user.breedchains) this.user.breedchains = { icon: {}, days: {}, name: {}};
                this.user.breedchains.icon[id] = icon;
                this.user.breedchains.days[id] = days;
                this.user.breedchains.name[id] = name;



                $('#new_chain').modal('hide');
                $('ul.timeline').append(html);
                this.chainName = '';
                this.chainDays = '';


            },

            removeChain: function(id){
                $('li.' + id).remove();
                delete this.user.breedchains.icon[id];
                delete this.user.breedchains.days[id];
                delete this.user.breedchains.name[id];

            }
        },
        ready: function () {
        },

    };

})();
(function () {
    App.Components.NotificationTab = {
        template: "#notification-tab-template",
        data: function () {
            return {
                activeTasks: []
            }
        },
        props: [],
        computed: {},
        watch:{},
        components: {
            'litter-weight': App.Components.LitterWeight,
            'litter-butcher': App.Components.LitterButcher,
        },
        events: {
            'notification-tab-reload-tasks': function(msg){
                this.loadTasks();
            }
        },
        methods: {
            openManagingModal: function(task){
                var router = new VueRouter();

                if(task.type == 'general' || task.icon == 'fa-venus-mars bg-blue' || task.icon == 'fa-check bg-maroon'){
                    router.go("/schedule");
                    return false;
                }

                if(task.icon.indexOf('fa-birthday-cake') >= 0) {
                    App.vent.openBirthModal();
                }

                if(task.icon.indexOf('fa-balance-scale') >= 0) {
                    App.vent.weightModal();
                }

                if(task.icon.indexOf('fa-cutlery') >= 0) {
                    App.vent.butcherModal();
                }
            },
            getFullTaskName: function(task){
                if(task.holderName){
                    if(task.type == 'breeder'){
                        return task.holderName + ': ' + task.name;
                    }
                    if(task.type == 'litter'){
                        return 'Litter ' + task.holderName + ': ' + task.name;
                    }
                }
                return task.name;
            },
            loadTasks: function(){
                var request = "/admin/users/upcoming";
                var self = this;
                this.$http.get(request, {}, function (data) {

                    this.activeTasks =_.filter(data, function(item){
                       return item.closed != '1';
                    });
                    setTimeout(self.loadTasks, 5000);
                });
            }

        },
        ready: function () {
            this.loadTasks();
        },
        attached: function () {},
        detached: function () {}
    };


    Vue.element('notification-tab', App.Components.NotificationTab);

})();

//This causes http://screencast.com/t/IJKDWwVIQMz
/*$.fn.bootstrapDP = $.fn.datepicker.noConflict();
App.initDatePicker = function () {
    if(!App.allmobiles()) {
        $('.input-group.date').each(function (i, el) {
            var disable_past = $(el).find('input').data('disable-past');
            $(el).bootstrapDP({
                format: App.dateFormat.toLowerCase(),
                todayBtn: "linked",
                autoclose: true,
                todayHighlight: true,
                toggleActive: true,
                defaultDate: new Date(),
                startDate: disable_past ? new Date() : 0
            });
        });
    }
};*/



(function (viewport) {

    App.settings = {
        debug: false,
        tablet: -1 != navigator.userAgent.indexOf("iPad"),
        desktop: null === navigator.userAgent.match(/(iPad)|(iPhone)|(iPod)|(android)|(webOS)/i),
        mobile: -1 != navigator.userAgent.indexOf("iPhone"),
        android: -1 != navigator.userAgent.toLowerCase().indexOf("android"),
        timer: null,

    };

    console.log(navigator.userAgent);
    console.log(App.settings);
    App.allmobiles = function() {
        //return true;
        var check = false;
        (function(a){if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i.test(a)||/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0,4)))check = true})(navigator.userAgent||navigator.vendor||window.opera);
        return check;
    };


    $(function () {

        $(document).on('keypress', '.js_only-numbers', function (eve) {
            if ((eve.which != 46 || $(this).val().indexOf('.') != -1) && (eve.which < 48 || eve.which > 57) || (eve.which == 46 && $(this).caret().start == 0)) {
                eve.preventDefault();
            }
        });

        $(document).on('keyup', '.js_only-numbers', function (eve) {
            if ($(this).val().indexOf('.') == 0) {
                $(this).val($(this).val().substring(1));
            }
        });


        $(document).keydown(function (e) {
            if (e.keyCode == 27) {
                $('.modal').modal('hide');
            }
        });



        $('.box-tools').on('click', function (e) {
            e.preventDefault();
            return false;
        });

        $('#sidebar-new-breeder').on('click', function () {
            App.vent.trigger('breeders.new');
            return false;
        });


        $('#sidebar-new-litter').on('click', function () {
            App.vent.trigger('litters.new');
            return false;
        });

        $(window).on('popstate', function() {
            $('.modal').modal('hide');
        });

        //if(App.settings.mobile) {
        //    $('aside.main-sidebar').find('a').on('click', function () {
        //        $('.sidebar-toggle').trigger('click');
        //    });
        //}

        if(viewport.is("xs")) {
            App.viewportXS = true;
        }

        $('aside.main-sidebar').find('li').not('.treeview').find('a').on('click', function () {
            if(App.viewportXS){
                $('.sidebar-toggle').trigger('click');
            }
        });

        $(window).resize(
            viewport.changed(function() {
                if(viewport.is('xs')) {
                    App.viewportXS = true;
                } else {
                    App.viewportXS = false;
                }
            })
        );


        App.formatDate = function(date){
            return moment(date).format(App.dateFormat);
        };

        App.vent.openBirthModal = function(){
            $('#birth').modal('show');
            return false;
        };

        App.vent.butcherModal = function(){
            $('#litter-butcher-modal').modal('show');
            return false;
        };

        App.vent.weightModal = function(){
            $('#litter-weight-modal').modal('show');
            return false;
        };

    });

})(ResponsiveBootstrapToolkit);

(function () {

    App.MobileTypes = function () {

        if(!App.allmobiles()) {
            return false;
        }

        $('[data-mobile-type]').each(function (i, el) {
            var $this = $(el);
            var type = $this.data('mobile-type');

            $this.attr('type', type);

            if(type == "date") {
                $this.focusout(function () {
                    $this.attr('type', 'text');
                    $this.val( moment($this.val()).format(App.dateFormat) );
                }).on('touchstart', function () {
                    if($this.attr('type') == "text") {
                        $this.attr('type', type);
                    }
                });
            }

        });

    };

})();
(function () {

    // Global app eVents
    App.vent = $('body');

    App.init = function () {

        Vue.use(VueResource);
        Vue.use(VueRouter);
        Vue.use(VueValidator);

        Vue.http.headers.common['X-CSRF-TOKEN'] = $('meta[name="csrf-token"]').attr('content');

        var router = new VueRouter();


        router.map({
            '/': {
                component: App.Components.Dashboard
            },
            '/breeders': {
                component: App.Components.Breeders
            },
            '/breeders/:action': {
                name: 'breeders',
                component: App.Components.Breeders
            },
            '/litters': {
                component: App.Components.Litters
            },
            '/litters/:action': {
                name: 'litters',
                component: App.Components.Litters
            },
            '/profile/:id': {
                component: App.Components.Profile
            },
            '/pedigree/:id': {
                component: App.Components.Pedigree
            },
            '/litterprofile/:id': {
                component: App.Components.LitterProfile
            },
            '/users': {
                component: App.Components.Users
            },
            '/users/:userId/edit': {
                name: 'userEdit',
                component: App.Components.User
            },
            '/users/create': {
                component: App.Components.User
            },
            '/schedule': {
                component: App.Components.Schedule
            },
            '/settings': {
                component: App.Components.Settings
            }
        });
        router.start(Vue, '#vue-app');

    };


})();
//# sourceMappingURL=vue-app.js.map
