(function () {

    App.Components.Settings = {
        template: "#settings-template",
        data: function () {
            return {
                user: {},
                user_id: "",
                success: {},
                errors: {}
            }
        },
        props: [],
        computed: {

        },

        methods: {
            updateSettings: function () {
                this.success = {};
                this.errors = {};

                this.$http.post('admin/users/'+this.user_id+'/settings', this.user, function (res) {
                    this.success = res;
                }).error(function (errors) {
                    this.errors = errors;
                });

            }
        },
        ready: function () {
        },

    };

})();