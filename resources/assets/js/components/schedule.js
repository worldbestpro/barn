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