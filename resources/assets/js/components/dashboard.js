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