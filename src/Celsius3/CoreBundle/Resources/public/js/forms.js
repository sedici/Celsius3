$.fn.modal.Constructor.prototype.enforceFocus = function () {
};

$('.summernote').summernote({
    height: 200
});

function getOldValues() {
    var values = [];
    var form_name = 'celsius3_corebundle_ordertype_materialData';
    values[form_name + '_title'] = $('#' + form_name + '_title').val();
    values[form_name + '_authors'] = $('#' + form_name + '_authors').val();
    values[form_name + '_year'] = $('#' + form_name + '_year').val();
    values[form_name + '_startPage'] = $('#' + form_name + '_startPage').val();
    values[form_name + '_endPage'] = $('#' + form_name + '_endPage').val();
    return values;
}

function noLibrarian(id) {
    $('#celsius3_corebundle_ordertype_originalRequest_owner_autocomplete').attr('disabled', 'disabled');
    $('#celsius3_corebundle_ordertype_originalRequest_owner').val(id);
    $('#celsius3_corebundle_ordertype_originalRequest_librarian').val('');
}

function librarian(id) {
    $('#celsius3_corebundle_ordertype_originalRequest_owner_autocomplete').removeAttr('disabled');
    $('#celsius3_corebundle_ordertype_originalRequest_owner').val('');
    $('#celsius3_corebundle_ordertype_originalRequest_librarian').val(id);
}

function getCatalogId(inputName) {
    return inputName.substr(7);
}

function registerSearch() {
    var input = $(this);
    var catalogId = getCatalogId(input.attr('name'));
    $.ajax({
        url: Routing.generate('admin_catalog_search_mark'),
        dataType: 'json',
        data: 'order_id=' + document_id + '&instance_id=' +
            instance_id + '&catalog_id=' + catalogId + '&result=' +
            input.val()
    }).done(function (data) {
        input.parent().siblings('.catalog-result').text(data.date);
    });
}

$('.mark-catalog').click(registerSearch);

/**
 * Turns default date picker into a jQuery UI widget
 */
if (_locale !== "en") {
    $.datepicker.setDefaults($.datepicker.regional[_locale]);
} else {
    $.datepicker.setDefaults($.datepicker.regional[""]);
}
$('.date').datepicker({
    changeMonth: true,
    changeYear: true,
    dateFormat: 'dd-mm-yy',
    yearRange: 'c-100:c',
    maxDate: new Date()
});

/**
 * Autocomplete fields related event
 */
$(document).on("focus", "input.autocomplete:not(.ui-autocomplete-input)", function () {
    $(this).autocomplete({
        source: function (request, response) {
            var field = $(this);
            $.ajax({
                url: ajax_path,
                dataType: "json",
                data: {
                    term: request.term,
                    target: field[0].element.attr('target')
                },
                success: function (data) {
                    response(data);
                }
            });
        },
        minLength: 2,
        select: function (event, ui) {
            var id = $(this).attr('id').replace('_autocomplete', '', 'gi');
            $('#' + id).val(ui.item.id);
        }
    });
});

$(document).on("focus", "input.autocomplete_multi:not(.ui-autocomplete-input)", function () {
    function split(val) {
        return val.split(/,\s*/);
    }

    function extractLast(term) {
        return split(term).pop();
    }

    $(this).autocomplete({
        source: function (request, response) {
            var field = $(this);
            $.ajax({
                url: ajax_path,
                dataType: "json",
                data: {
                    term: extractLast(request.term),
                    target: field[0].element.attr('target')
                },
                success: function (data) {
                    response(data);
                }
            });
        },
        search: function () {
            // custom minLength
            var term = extractLast(this.value);
            if (term.length < 2) {
                return false;
            }
        },
        focus: function () {
            // prevent value inserted on focus
            return false;
        },
        select: function (event, ui) {
            var terms = split(this.value);
            // remove the current input
            terms.pop();
            // add the selected item
            terms.push(ui.item.id);
            // add placeholder to get the comma-and-space at the end
            terms.push("");
            this.value = terms.join(", ");

            return false;
        }
    });
});

var loadMaterialData = function () {
    var oldValues = getOldValues();
    var urlChange;

    if ((user_role === 'ROLE_SUPER_ADMIN') || (user_role === 'ROLE_ADMIN')) {
        urlChange = Routing.generate('admin_order_change');
    } else {
        urlChange = Routing.generate('user_order_change');
    }

    $.ajax({
        type: 'POST',
        url: urlChange,
        data: {
            material: $('#celsius3_corebundle_ordertype_materialDataType').val()
        },
        success: function (data) {
            $('div#material_data').html(data);
            for (key in oldValues) {
                $('#' + key).val(oldValues[key]);
            }
        }
    });
};
/**
 * Material type change related event
 */
$('#celsius3_corebundle_ordertype_materialDataType')
        .change(loadMaterialData);

$('#celsius3_corebundle_ordertype_instance').change(function () {
    $('#celsius3_corebundle_ordertype_owner_autocomplete').val('');
    $('#celsius3_corebundle_ordertype_owner').val('');
});

if (user_exists) {
    // Controles para los widgets del formulario de carga de pedidos de un
    // bibliotecario
    if ($('#celsius3_corebundle_ordertype_originalRequest_target').length > 0) {
        noLibrarian(user_id);
    }

    $('#celsius3_corebundle_ordertype_originalRequest_target').change(function () {
        if ($('#celsius3_corebundle_ordertype_originalRequest_target').val() === 'me') {
            noLibrarian(user_id);
        } else {
            librarian(user_id);
        }
    });
}

$('.union_link, .enable_link').click(function () {
    $('#batch-action').val($(this).attr('class').split('_')[0]);
    if ($('input[type=checkbox]:checked').length >= 2) {
        $('.batch_form').submit();
    }
});

/*
 * News date edition
 */
$('#celsius3_corebundle_newstype_date').datetimepicker({
    showSecond: true
});
var dateWidgets = $('#celsius3_corebundle_newstype_date');
dateWidgets.hide();
$('.news-date').parent().append(
        '<div class="date-text form-control">' + news_date +
        '</div><div><a class="show-date-widget btn btn-default">' +
        news_text_change + '</a></div>');
$(document).on('click', '.show-date-widget', function () {
    $('.news-date').parent().children('div').hide();
    dateWidgets.show();
});

/*
 * Como no se utiliza accordion-group hay que ocultar "a mano" los elementos
 * mostrados cuando se presiona otro link en el flujo de Order
 */
$('.state-list a.pointer').click(function (e) {
    $('#state-info .in').collapse('hide');
});

/*
 * Agregado de campos extra para la subida de archivos al recibir un Order
 */
$('.add-file').click(
        function () {
            var collectionHolder = $(this).parents('form').find(
                    '#celsius3_corebundle_orderreceivetype_files');
            console.log(collectionHolder);
            collectionHolder.data('index',
                    collectionHolder.find(':input').length);
            var index = collectionHolder.data('index');
            var newWidget = collectionHolder.data('prototype');
            newWidget = newWidget.replace(/__name__label__/g, '');
            newWidget = newWidget.replace(/__name__/g, index);
            collectionHolder.data('index', index + 1);
            collectionHolder.append(newWidget);
            return false;
        });

/*
 * Select especial para mensajes
 */
//$('#message_recipients_autocomplete').select2({
//    placeholder: '',
//    query: function (query) {}
//});

/*
 * Form submission
 */
$('.delete-message').click(function () {
    $(this).parent('form').submit();
});

$('.submit-download-form').click(function () {
    var form = $(this).parent();
    form.submit();
    form.remove();
});

$(document).on('click', '.submit-form', function () {
    $(this).parent().submit();
});

$(document).on('click', '.doSubmit', function () {
    $($(this).attr('value')).submit();
});

/*
 * Form batch selection
 */
$('.check-all').click(function () {
    $('.batch-checkbox').prop('checked', 'checked');
});

$('.uncheck-all').click(function () {
    $('.batch-checkbox').prop('checked', '');
});
