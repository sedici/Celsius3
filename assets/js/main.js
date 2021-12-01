require('jquery');
require('select2');
require('select2/dist/css/select2.min.css');
require('select2-bootstrap-theme/dist/select2-bootstrap.min.css');

require('jquery-ui-dist/jquery-ui.min.js');
require('jquery-slimscroll');
require('fastclick');
require('mousetrap');
require('jquery-datepicker');

require('bootstrap-sass');
require('bootstrap-notify');
require('bootstrap-switch');
require('summernote');
require('summernote/dist/summernote.css');

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