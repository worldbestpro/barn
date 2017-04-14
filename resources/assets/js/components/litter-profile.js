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