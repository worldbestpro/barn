(function () {

    App.Components.User = {
        template: "#user-template",
        data: function () {
            return {
                userRoles: [],
                errors: [],
                editMode: false,
                user: {
                    "_method": "POST",
                    "image": {
                        "name": '',
                        "path": '',
                        "temp": true,
                        "oldImage": '',
                        "delete": false,
                    },
                }
            }
        },
        computed: {
            formPath: function () {
                if (!this.editMode)
                    return '/admin/users';
                else
                    return '/admin/users/' + this.user.id;
            },
        },
        events: {
            dataHere: function () {
                this.$nextTick(function () {
                    this.initUploader();

                });
            }
        },
        methods: {
            deleteImage: function () {
                this.user.image.path = '';
                this.user.image.delete = true;
            },
            sendUser: function () {
                this.errors = [];
                if (this.editMode)
                    this.user._method = "PUT";
                //this.user.image = this.images;
                this.$http.post(this.formPath, this.user, function () {
                    this.$route.router.go({path: '/users'});
                }).error(function (data) {
                    if (data) {
                        this.errors = data;
                    }
                });
            },
            initUploader: function () {
                var self = this;
                $(self.$els['image']).fileupload({
                    dataType: 'json',
                    paramName: 'image',
                    formData: {
                        "_token": $('meta[name="csrf-token"]').attr('content'),
                        "_method": "POST",
                        "user_id": self.user.id
                    },
                    url: '/admin/images/uploadImage',
                    done: function (e, data) {
                        self.user.image.name = data.result.image.name;
                        self.user.image.path = data.result.image.path;
                        self.user.image.temp = data.result.image.temp;
                    }
                });
            }
        },
        ready: function () {
            this.$http.get('/admin/roles/getList', function (rolesList) {
                this.userRoles = rolesList;
            });
            if (this.$route.params.userId) {
                this.editMode = true;
                this.$http.get('/admin/users/' + this.$route.params.userId, function (user) {
                    this.user = user;
                });
            }
            this.initUploader();
        }
    };

})();