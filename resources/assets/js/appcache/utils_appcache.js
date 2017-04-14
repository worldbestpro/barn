(function (App) {

    App.get = function (name) {
        return localStorage.getItem(name);
    };

    App.set = function (name, value) {
        return localStorage.setItem(name, value);
    };

    App.online = function () {
        return navigator.onLine;
    };

    online = true;
    App.onlineCheck = function () {
        console.log('online check')
        if (online != App.online()) {
            online = App.online();
            var $body = $("body");
            if(online) {
                $body.removeClass("offline");
                console.log("||| Online ---")
            } else {
                $body.addClass("offline")
                console.log("XXX Offline ---")
            }
        }
        App.showDevData();
    };

    App.validationErrors = function (form, errors) {
        var $form = $(form);
        $form.find('.help-block.error').remove();

        $.each(errors, function (input, error) {
            var $input = $form.find('[name="'+input+'"]');
            $input.parent().addClass('has-error').before('<span class="help-block error"><strong>'+error+'</strong></span>');

            $input.on('change keydown', function () {
                $input.parent().removeClass('has-error');
                $input.off('change keydown');
            });
        })

    },

    App.showDevData = function () {
        var user = App.get('auth') ? App.get('auth').split(',') : [];
        $('#online-acc').text( user.length ? user[0] : "null" );
        $('#online-stat').text( sessionStorage['status'] || "null" );
    }

    $(window).bind('online offline', App.onlineCheck);
    setInterval(App.onlineCheck, 5000);

    App.onlineCheck();
    App.showDevData();

    
})(window.App);