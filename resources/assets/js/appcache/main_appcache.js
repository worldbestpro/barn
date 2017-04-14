(function () {

    App.init = function () {
        console.log('App init!');

        if(sessionStorage['status'] != "logged") {
            $('.login-window').removeClass('hide');
            $('.login-window .login-box').removeClass('hide');
        } else {
            $('.login-window').addClass('hide');
        }

        window.applicationCache.addEventListener('updateready', function(e) {
            if (confirm('A new version of this site is available. Load it?'))
                window.location.reload();
        }, false);


        $('#logout').click(function () {
            sessionStorage['status'] = null;
            if(!App.online()) {
                $('.login-window').removeClass('hide');
                return false;
            }
        });


    }


})();