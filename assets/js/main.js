require('jquery');
require('select2');
require('jquery-ui-dist/jquery-ui.js');
require('jquery-slimscroll');
require('fastclick');
require('mousetrap');
require('jquery-datepicker');

require('bootstrap');
require('bootstrap-notify');
require('bootstrap-switch');
// require('summernote');

require('icanhaz');
require('underscore');

require('datatables');
require('datatables.net');

require('when');
require('fabric');

// Este fragmento corrige el ancho del "autocomplete"
jQuery.ui.autocomplete.prototype._resizeMenu = function () {
    'use strict';
    var ul = this.menu.element;
    ul.outerWidth(this.element.outerWidth());
};
// -------------------------------------------------