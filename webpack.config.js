var Encore = require('@symfony/webpack-encore');
// var webpack = require('webpack');

Encore
    .setOutputPath('public/build/')
    .setPublicPath('/build')
    .cleanupOutputBeforeBuild()
    .addEntry('vendor', [
        'jquery',
        'jquery-ui',
        'bootstrap-sass',
        'angular',
        'icanhaz',
        'underscore'
    ])
    .addEntry('celsius3core', [
        './public/bundles/celsius3core/js/main.js',
        './public/bundles/celsius3core/js/jquery-ui-timepicker-addon.js',
        './public/bundles/celsius3core/js/cosmetics.js',
        './public/bundles/celsius3core/js/forms.js',
        './public/bundles/celsius3core/js/cci.js',
        './public/bundles/celsius3core/js/admin.js',
        './public/bundles/celsius3core/js/dnd.js',
        './public/bundles/celsius3core/js/mail.js',
        './public/bundles/celsius3core/js/alert.js'
    ])
    .addEntry('celsius3notification', [
        './public/bundles/celsius3notification/js/autobahn.min.js',
        './public/bundles/celsius3notification/js/notification.js'
    ])
    .addEntry('angular', [
        './public/bundles/celsius3core/js/angular.js',
    ])
    .addEntry('administration', [
        './public/bundles/celsius3core/ng/Administration/controllers.js',
        './public/bundles/celsius3core/ng/Administration/filters.js',
        './public/bundles/celsius3core/ng/Administration/app.js',
        './public/bundles/celsius3core/ng/Administration/resources.js'
    ])
    .addEntry('admin_order', [
        './public/bundles/celsius3core/ng/AdminOrder/app.js',
        './public/bundles/celsius3core/ng/AdminOrder/resources.js',
        './public/bundles/celsius3core/ng/AdminOrder/controllers.js',
        './public/bundles/celsius3core/ng/AdminOrder/filters.js'
    ])
    .addEntry('cci', [
        './public/bundles/celsius3core/ng/CCI/app.js',
        './public/bundles/celsius3core/ng/CCI/directives.js',
        './public/bundles/celsius3core/ng/CCI/resources.js'
    ])
    .addEntry('state_bar', [
        './public/bundles/celsius3core/ng/StateBar/app.js',
        './public/bundles/celsius3core/ng/StateBar/directives.js'
    ])
    .addEntry('admin_base_user', [
        './public/bundles/celsius3core/ng/AdminBaseUser/app.js',
        './public/bundles/celsius3core/ng/AdminBaseUser/controllers.js',
        './public/bundles/celsius3core/ng/AdminBaseUser/filters.js'
    ])
    .addEntry('user', [
        './public/bundles/celsius3core/ng/User/app.js',
        './public/bundles/celsius3core/ng/User/resources.js',
        './public/bundles/celsius3core/ng/User/controllers.js',
        './public/bundles/celsius3core/ng/User/filters.js'
    ])
    .addEntry('admin_institution', [
        './public/bundles/celsius3core/ng/AdminInstitution/app.js',
        './public/bundles/celsius3core/ng/AdminInstitution/controllers.js',
        './public/bundles/celsius3core/ng/AdminInstitution/filters.js'
    ])
    .addEntry('superadministration', [
        './public/bundles/celsius3core/js/dash_table.js'
    ])
    .addEntry('statistics', [
        './public/bundles/celsius3core/js/statistics.js',
        './public/bundles/celsius3core/ng/Statistics/app.js',
        './public/bundles/celsius3core/ng/Statistics/controllers.js',
        './public/bundles/celsius3core/ng/Statistics/filters.js'
    ])
    .addEntry('locales_es', [
        './public/bundles/celsius3core/js/jquery.ui.datepicker-es.min.js',
        './public/bundles/celsius3core/js/select2_locale_es.js',
    ])
    .addEntry('locales_pt', [
        './public/bundles/celsius3core/js/jquery.ui.datepicker-pt.min.js',
        './public/bundles/celsius3core/js/select2_locale_pt-BR.js',
    ])
    .addEntry('login', [
        './public/bundles/celsius3core/js/login.js'
    ])
    .addEntry('search',[
        './public/bundles/celsius3core/js/search.js'
    ])
    .addStyleEntry('global', './public/bundles/celsius3core/scss/celsius3.scss')
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