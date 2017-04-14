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