var Encore = require('@symfony/webpack-encore');
var webpack = require('webpack');

Encore
    .setOutputPath('web/build/')
    .setPublicPath('/build')
    .cleanupOutputBeforeBuild()
    .createSharedEntry('vendor', [
        'jquery',
        'jquery-ui',
        'bootstrap-sass',
        'angular',
        'icanhaz',
        'underscore'
    ])
    .addEntry('celsius3core', [
        './web/bundles/celsius3core/js/main.js',
        './web/bundles/celsius3core/js/jquery-ui-timepicker-addon.js',
        './web/bundles/celsius3core/js/cosmetics.js',
        './web/bundles/celsius3core/js/forms.js',
        './web/bundles/celsius3core/js/cci.js',
        './web/bundles/celsius3core/js/admin.js',
        './web/bundles/celsius3core/js/dnd.js',
        './web/bundles/celsius3core/js/mail.js',
        './web/bundles/celsius3core/js/alert.js'
    ])
    .addEntry('celsius3notification', [
        './web/bundles/celsius3notification/js/autobahn.min.js',
        './web/bundles/celsius3notification/js/notification.js'
    ])
    .addEntry('angular', [
        './web/bundles/celsius3core/js/angular.js',
    ])
    .addEntry('administration', [
        './web/bundles/celsius3core/ng/Administration/controllers.js',
        './web/bundles/celsius3core/ng/Administration/filters.js',
        './web/bundles/celsius3core/ng/Administration/app.js',
        './web/bundles/celsius3core/ng/Administration/resources.js'
    ])
    .addEntry('admin_order', [
        './web/bundles/celsius3core/ng/AdminOrder/app.js',
        './web/bundles/celsius3core/ng/AdminOrder/resources.js',
        './web/bundles/celsius3core/ng/AdminOrder/controllers.js',
        './web/bundles/celsius3core/ng/AdminOrder/filters.js'
    ])
    .addEntry('cci', [
        './web/bundles/celsius3core/ng/CCI/app.js',
        './web/bundles/celsius3core/ng/CCI/directives.js',
        './web/bundles/celsius3core/ng/CCI/resources.js'
    ])
    .addEntry('state_bar', [
        './web/bundles/celsius3core/ng/StateBar/app.js',
        './web/bundles/celsius3core/ng/StateBar/directives.js'
    ])
    .addEntry('admin_base_user', [
        './web/bundles/celsius3core/ng/AdminBaseUser/app.js',
        './web/bundles/celsius3core/ng/AdminBaseUser/controllers.js',
        './web/bundles/celsius3core/ng/AdminBaseUser/filters.js'
    ])
    .addEntry('user', [
        './web/bundles/celsius3core/ng/User/app.js',
        './web/bundles/celsius3core/ng/User/resources.js',
        './web/bundles/celsius3core/ng/User/controllers.js',
        './web/bundles/celsius3core/ng/User/filters.js'
    ])
    .addEntry('admin_institution', [
        './web/bundles/celsius3core/ng/AdminInstitution/app.js',
        './web/bundles/celsius3core/ng/AdminInstitution/controllers.js',
        './web/bundles/celsius3core/ng/AdminInstitution/filters.js'
    ])
    .addEntry('superadministration', [
        './web/bundles/celsius3core/js/dash_table.js'
    ])
    .addEntry('statistics', [
        './web/bundles/celsius3core/js/statistics.js',
        './web/bundles/celsius3core/ng/Statistics/app.js',
        './web/bundles/celsius3core/ng/Statistics/controllers.js',
        './web/bundles/celsius3core/ng/Statistics/filters.js'
    ])
    .addEntry('locales_es', [
        './web/bundles/celsius3core/js/jquery.ui.datepicker-es.min.js',
        './web/bundles/celsius3core/js/select2_locale_es.js',
    ])
    .addEntry('locales_pt', [
        './web/bundles/celsius3core/js/jquery.ui.datepicker-pt.min.js',
        './web/bundles/celsius3core/js/select2_locale_pt-BR.js',
    ])
    .addEntry('login', [
        './web/bundles/celsius3core/js/login.js'
    ])
    .addEntry('search',[
        './web/bundles/celsius3core/js/search.js'
    ])
    .addStyleEntry('global', './web/bundles/celsius3core/scss/celsius3.scss')
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