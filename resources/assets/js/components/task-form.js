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