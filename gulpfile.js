var elixir = require('laravel-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */
elixir.extend('sourcemaps', false);

elixir(function (mix) {

    mix.less([
        'bootstrap-less/bootstrap.less',
        'AdminLTE/AdminLTE.less',
        'AdminLTE/skins/_all-skins.less',
        'fontawesome/font-awesome.less'
    ], 'public/css/libs.css');

    //mix.less('custom.less', 'public/css/custom.css');


    /*  PLUGINS  */

// plugins path
    var plugins = "../plugins/";

// plugins scripts
    mix.scripts([
        plugins + 'jQuery/jQuery-2.2.0.min.js',
        plugins + 'jQueryUI/jquery-ui.min.js',
        plugins + 'responsive-bootstrap-toolkit/bootstrap-toolkit.min.js',
        plugins + 'daterangepicker/moment.min.js',
        plugins + 'fastclick/fastclick.js',
        plugins + 'datepicker/bootstrap-datepicker.js',
        plugins + 'daterangepicker/daterangepicker.js',
        plugins + 'bootstrap-wysihtml5/bootstrap3-wysihtml5.all.js',
        plugins + 'knob/jquery.knob.js',
        plugins + 'iCheck/icheck.js',
        plugins + 'iCheck/icheck.js',
        plugins + 'typeahead/typeahead.bundle.js',
        plugins + 'typeahead/typeahead.jquery.js',
    ], 'public/js/plugins.js');

// plugins css
    mix.styles([
        plugins + 'datepicker/datepicker3.css',
        plugins + 'daterangepicker/daterangepicker-bs3.css',
        plugins + 'bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css',
        plugins + 'iCheck/square/_all.css',
        plugins + 'bootstrap-horizon/bootstrap-horizon.css',
        plugins + 'typeahead/typeahead.css',
    ], 'public/css/plugins.css');

// plugins css
//    mix.styles([
//        '../../../AdminLTE-master/custom.css'  // client styles!!!
//    ], 'public/css/custom.css');


    /*  CUSTOM JS  */

    mix.scripts([
        'libs/registerElement.js',
        'libs/vue.js',
        'libs/vue-router.js',
        'libs/vue-resource.js',
        'libs/vue-validator.js',
        'libs/vue-element.js',
        'libs/underscore.js',
        'libs/jquery.fileupload.js',
        'libs/bootstrap.min.js',
        'libs/admin-app.js',
        //'libs/sha1.js',
        'demo.js',
        //'pages/dashboard.js'
    ], 'public/js/libs.js');

    mix.scripts([
        'config.js',
        'components/sex-select.js',
        'components/image-upload.js',
        'directives/datepicker.js',
        'components/task-form.js',
        'components/breeder-form.js',
        'components/litter-form.js',
        'components/litter-box.js',
        'components/litter-butcher.js',
        'components/litter-weight.js',
        'components/kit-form.js',
        'components/userList.js',
        'components/user.js',
        'components/schedule-calendar.js',
        'components/upcomming-tasks.js',
        'components/schedule.js',
        'components/dashboard.js',
        'components/breeders.js',
        'components/litters.js',
        'components/litter-profile.js',
        'components/profile.js',
        'components/settings.js',
        'components/notification-tab.js',
        'custom.js',
        'mobiletypes.js',
        'vue-app.js',
    ], 'public/js/vue-app.js');

});
