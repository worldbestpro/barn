(function () {

    App.Components.KitForm = {
        template: "#kit-form-template",
        data: function () {
            return {}
        },
        props: ['kit', 'litter'],
        watch: {
            kit: function () {
                $('.js_icheck-kit-blue, .js_icheck-kit-red').iCheck('update');
            }
        },
        components: {
            'image-upload': App.Components.ImageUpload
        },
        computed: {
            kit_weight: function () {
                return _.map(this.kit.weight, function (val, i) {
                    return {id: i, value: val};
                }.bind(this));
            }
        },
        methods: {
            kitDied: function (kit) {
                this.$http.get('admin/kits/' + kit.id + '/died', function () {
                    this.$dispatch('refresh-kits', kit);
                });
                $('#kit-form-modal').modal('hide');
            },

            makeBreeder: function (kit) {
                kit._method = "PUT";
                kit.weight_changed = 1;
                this.$http.post('admin/kits/' + kit.id, kit, function () {

                    this.$http.get('admin/kits/' + kit.id + '/makeBreeder', function (res) {
                        $('#kit-form-modal').modal('hide');
                        this.$router.go({path: '/profile/' + res.id});
                    });

                });
            },

            archive: function (kit) {
                this.$http.post('admin/kits/' + kit.id + '/archive', {archived: 1}, function () {
                    this.$dispatch('refresh-kits', kit);
                });
                $('#kit-form-modal').modal('hide');
            },

            unarchive: function (kit) {
                this.$http.post('admin/kits/' + kit.id + '/archive', {archived: 0}, function () {
                    this.$dispatch('refresh-kits', kit);
                });
                $('#kit-form-modal').modal('hide');
            },

            kitDelete: function (kit) {
                this.$http.post('admin/kits/' + kit.id, {_method: "DELETE"}, function () {
                    this.$dispatch('refresh-kits', kit);
                });
                $('#kit-form-modal').modal('hide');
            },

            saveKit: function (_kit) {
                var kit = _.extend({}, _kit);
                kit._method = "PUT";
                kit.weight_changed = 1;


                if (kit.new_weight) {
                    if (kit.weight == null)
                        kit.weight = [];
                    kit.weight.push(kit.new_weight);
                }

                this.$http.post('admin/kits/' + kit.id, kit, function () {
                    this.$dispatch('refresh-kits', kit);
                });
                $('#kit-form-modal').modal('hide');
            },

            father: function () {
                return _.find(this.litter.parents, function (item) {
                    return item.sex == "buck"
                });
            },
            mother: function () {
                return _.find(this.litter.parents, function (item) {
                    return item.sex == "doe"
                });
            },

        },
        ready: function () {
            $('.js_icheck-kit-blue').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue'
            }).on('ifChecked', function (event) {
                this.kit.sex = "buck";
            }.bind(this));

            //Red color scheme for iCheck
            $('.js_icheck-kit-red').iCheck({
                checkboxClass: 'icheckbox_square-red',
                radioClass: 'iradio_square-red'
            }).on('ifChecked', function (event) {
                this.kit.sex = "doe";
            }.bind(this));
        }

    };

})();