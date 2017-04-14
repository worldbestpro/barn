(function () {

    App.Components.ImageUpload = {
        template: "#image-upload-template",
        data: function () {
            return {
                progress: 0,
                loading: 0
            }
        },
        props: ['breeder'],
        watch: {
/*
            breeder: function () {
                this.initUploader();
            }
*/
        },
        computed: {
        },
        methods: {
            initUploader: function () {
                var self = this;
                $(this.$els['image']).fileupload({
                    dataType: 'json',
                    paramName: 'image',
                    formData: {
                        _method: "POST",
                        _token: App.token
                    },
                    url: '/admin/images/uploadImage',
                    send: function () {
                        self.loading = 1;
                    },
                    done: function (e, data) {
                        self.loading = 0;
                        if(this.breeder.image) {
                            this.breeder.image.name = data.result.image.name;
                            this.breeder.image.path = data.result.image.path;
                            this.breeder.image.temp = data.result.image.temp;
                        }
                        this.$broadcast('image-uploaded', data.result.image);
                    }.bind(this)
                });
            },
            uploaderHelper: function () {
                $('#breeder-fileupload').click();
            },
            deleteImage: function () {
                this.breeder.image = {name: "", path: "", temp: true, oldImage: "", delete: false};
                $('#breeder-fileupload').change();
            }
        },
        ready: function () {
            this.initUploader();
        }

    };

})();