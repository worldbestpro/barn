(function () {

    App.Login = function () {

        console.log('Login script');


        $('.icheck').iCheck('check');

        $('#login-form').on('submit', function (e) {
            e.preventDefault();
            $('#ajax-errors').addClass('hide');

            var form = this;

            if(App.online()) {

                var formData = new FormData(form);
                $.ajax({
                    type: "POST",
                    url: "/login",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success:function(data){
                        console.log(data);
                        $('.login-window').addClass('hide');
                        App.user = data;

                        localStorage['auth'] = data.email + "," + data.password;
                        sessionStorage['status'] = "logged";
                        App.showDevData();

                        App.init();
                    },
                    error: function (err) {
                        App.validationErrors(form, err.responseJSON);
                    }
                });

            } else {

                var email = $('#email').val();
                var passwd =  $('#passwd').val();

                if(localStorage['auth']) {
                    var userd = localStorage['auth'].split(',');

                    var hash = Sha1.hash(passwd);

                    if(email == userd[0] && hash == userd[1]) {

                        sessionStorage['status'] = "logged";
                        $('.login-window').addClass('hide');
                        App.init();
                    } else {
                        $('#ajax-errors').text('Login information not match with saved offline account.')
                            .removeClass('hide');
                    }
                } else {
                    $('#ajax-errors').text('We don\'t have offline login credentials for you account.')
                        .removeClass('hide');
                }

            }

            return false;

        });

    };

})();