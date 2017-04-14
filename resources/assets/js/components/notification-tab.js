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