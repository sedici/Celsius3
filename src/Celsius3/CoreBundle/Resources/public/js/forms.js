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
    $('#celsius3_corebundle_ordertype_owner_autocomplete').attr('disabled',
            'disabled');
    $('#celsius3_corebundle_ordertype_owner').val(id);
    $('#celsius3_corebundle_ordertype_librarian').val('');
}

function librarian(id) {
    $('#celsius3_corebundle_ordertype_owner_autocomplete').removeAttr(
            'disabled');
    $('#celsius3_corebundle_ordertype_owner').val('');
    $('#celsius3_corebundle_ordertype_librarian').val(id);
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
        data: 'order_id=' + document_id + '&instance_id='
                + instance_id + '&catalog_id=' + catalogId + '&result='
                + input.val()
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
$('input.autocomplete').autocomplete({
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

/**
 * Material type change related event
 */
$('#celsius3_corebundle_ordertype_materialDataType').change(
        function () {
            var oldValues = getOldValues();
            $.ajax({
                type: 'POST',
                url: Routing.generate('admin_order_change'),
                data: {
                    material: $(
                            '#celsius3_corebundle_ordertype_materialDataType')
                            .val()
                },
                success: function (data) {
                    $('#celsius3_corebundle_ordertype_materialData_title')
                            .parent().parent().parent().html(data);
                    for (key in oldValues) {
                        $('#' + key).val(oldValues[key]);
                    }
                }
            });
        });

$('#celsius3_corebundle_ordertype_instance').change(function () {
    $('#celsius3_corebundle_ordertype_owner_autocomplete').val('');
    $('#celsius3_corebundle_ordertype_owner').val('');
});

if (user_exists) {
    // Controles para los widgets del formulario de carga de pedidos de un
    // bibliotecario
    if ($('#celsius3_corebundle_ordertype_target').length > 0) {
        noLibrarian(user_id);
    }

    $('#celsius3_corebundle_ordertype_target').change(function () {
        if ($('#celsius3_corebundle_ordertype_target').val() === 'me') {
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
        '<div class="date-text form-control">' + news_date
        + '</div><div><a class="show-date-widget btn btn-default">'
        + news_text_change + '</a></div>');
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
$('#message_recipients').select2({
    placeholder: ''
});

/*
 * Select especial para journals
 */
$('#celsius3_corebundle_ordertype_materialData_journal').select2();

$('#celsius3_corebundle_ordertype_materialData_journal').on('change', function () {
    if ($('#celsius3_corebundle_ordertype_materialData_journal').val() === '') {
        $('#celsius3_corebundle_ordertype_materialData_other').prop('disabled', false);
    } else {
        $('#celsius3_corebundle_ordertype_materialData_other').prop('disabled', true);
    }
});

$('#celsius3_corebundle_ordertype_materialData_journal').change();

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