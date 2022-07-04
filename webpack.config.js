const Encore = require('@symfony/webpack-encore');

Encore
    .setOutputPath('public/build/')
    .setPublicPath('/build')
    .cleanupOutputBeforeBuild()
    .addEntry('celsius3core', [
        './assets/js/main.js',
        './assets/js/jquery-ui-timepicker-addon.js',
        './assets/js/cosmetics.js',
        './assets/js/forms.js',
        './assets/js/cci.js',
        './assets/js/admin.js',
        './assets/js/dnd.js',
        './assets/js/mail.js',
        './assets/js/alert.js'
    ])
    .addEntry('celsius3notification', [
        './assets/js/autobahn.min.js',
        './assets/js/notification.js'
    ])
    .addEntry('angular', [
        './assets/js/angular.js',
    ])
    .addEntry('administration', [
        './assets/ng/Administration/controllers.js',
        './assets/ng/Administration/filters.js',
        './assets/ng/Administration/app.js',
        './assets/ng/Administration/resources.js'
    ])
    .addEntry('admin_order', [
        './assets/ng/AdminOrder/app.js',
        './assets/ng/AdminOrder/resources.js',
        './assets/ng/AdminOrder/controllers.js',
        './assets/ng/AdminOrder/filters.js'
    ])
    .addEntry('cci', [
        './assets/ng/CCI/app.js',
        './assets/ng/CCI/directives.js',
        './assets/ng/CCI/resources.js'
    ])
    .addEntry('state_bar', [
        './assets/ng/StateBar/app.js',
        './assets/ng/StateBar/directives.js'
    ])
    .addEntry('admin_base_user', [
        './assets/ng/AdminBaseUser/app.js',
        './assets/ng/AdminBaseUser/controllers.js',
        './assets/ng/AdminBaseUser/filters.js'
    ])
    .addEntry('user', [
        './assets/ng/User/app.js',
        './assets/ng/User/resources.js',
        './assets/ng/User/controllers.js',
        './assets/ng/User/filters.js'
    ])
    .addEntry('admin_institution', [
        './assets/ng/AdminInstitution/app.js',
        './assets/ng/AdminInstitution/controllers.js',
        './assets/ng/AdminInstitution/filters.js'
    ])
    .addEntry('superadministration', [
        './assets/js/dash_table.js'
    ])
    .addEntry('statistics', [
        './assets/js/statistics.js',
        './assets/ng/Statistics/app.js',
        './assets/ng/Statistics/controllers.js',
        './assets/ng/Statistics/filters.js'
    ])
    .addEntry('locales_es', [
        './assets/js/jquery.ui.datepicker-es.min.js',
        './assets/js/select2_locale_es.js',
    ])
    .addEntry('locales_pt', [
        './assets/js/jquery.ui.datepicker-pt.min.js',
        './assets/js/select2_locale_pt-BR.js',
    ])
    .addEntry('login', [
        './assets/js/login.js'
    ])
    .addEntry('search',[
        './assets/js/search.js'
    ])
    .addStyleEntry('global', './assets/scss/celsius3.scss')
    .enableSassLoader()
    .autoProvideVariables({
        _: 'underscore',
        $: 'jquery',
        jQuery: 'jquery',
        'window.$': 'jquery',
        'window.jQuery': 'jquery',
        when: 'when',
        ich: 'icanhaz',
        c3: 'c3'
    })
    .autoProvidejQuery()
    .enableSourceMaps(!Encore.isProduction())
    .enableVersioning(Encore.isProduction())
;

module.exports = Encore.getWebpackConfig();