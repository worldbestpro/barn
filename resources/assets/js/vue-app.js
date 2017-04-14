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