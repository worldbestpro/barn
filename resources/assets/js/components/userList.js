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