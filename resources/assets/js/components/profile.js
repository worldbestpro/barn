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