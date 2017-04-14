
Vue.directive('datepicker', {
    twoWay: true,
    bind: function (value) {
        var vm = this.vm;
        var key = this.expression;
        var self = this;
        var disable_past = $(this.el).find('input').data('disable-past');
        $(this.el).datepicker({
            format: App.dateFormat.toLowerCase(),
            todayBtn: "linked",
            autoclose: true,
            todayHighlight: true,
            disableTouchKeyboard: true,
            toggleActive: true,
            defaultDate: new Date(),
            startDate: disable_past ? new Date() : 0
        }).on('show', function(e) {
            /**
             * hack to disable touch keyboard with blur() method,
             * but only if the datepicker is not visible (to minimize accessibility problems)
             * so with a second click, the keyboard appears
             */
            //if ((window.navigator.msMaxTouchPoints || 'ontouchstart' in document)) {
            //    if (!isDatepickerVisible) {
            //        $('input', $(this))[0].blur();
            //    }
            //}
            //isDatepickerVisible = true;

        }).on('hide', function(e) {
            //isDatepickerVisible = false;
        }).on('changeDate', function(e){
            self.vm.$set(key, e.format());
        });

        $(this.el).datepicker('setDate', this.value);

    },
    update: function (value) {
        $(this.el).val(value);
        $(this.el).datepicker('setDate', value);
    },
    unbind: function () {
        $(this.el).datepicker('destroy');
    }
});
