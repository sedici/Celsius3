require('icheck');
require('icheck/skins/square/blue.css');

$(function () {
    'use strict';
    $('input').iCheck({
        checkboxClass: 'icheckbox_square-blue',
        radioClass: 'iradio_square-blue',
        increaseArea: '20%'
    });
});