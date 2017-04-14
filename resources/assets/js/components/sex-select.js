(function () {

    App.Components.SexSelect = {
        template: "#sex-select-template",
        data: function () {
            return {
            }
        },
        props: ['model'],
        computed: {
        },
        watch: {
            model: function () {
                $(this.$els.buck).iCheck('update');
                $(this.$els.doe).iCheck('update');
            }
        },
        methods: {
        },
        ready: function () {

            $(this.$els.buck).iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue'
            }).on('ifChecked', function(event){
                this.model.sex = "buck";
            }.bind(this));

            //Red color scheme for iCheck
            $(this.$els.doe).iCheck({
                checkboxClass: 'icheckbox_square-red',
                radioClass: 'iradio_square-red'
            }).on('ifChecked', function(event){
                this.model.sex = "doe";
            }.bind(this));
        }

    };

})();