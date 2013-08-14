function loadCities(json) {
    var city_data;
    city_data = {
        value: '',
        name: ''
    };
    $('select.city-select').append(ich.city(city_data));

    $.each(json, function(i, val) {
        var city_data;
        city_data = {
            value: val.value,
            name: val.name
        };
        $('select.city-select').append(ich.city(city_data));
    });
}

function loadInstitutions(json) {
    var institution_data;
    institution_data = {
        value: '',
        name: '',
        level: 1,
        hasChildren: false,
        children: [],
        form: form_name
    };
    $('select.institution-select').append(ich.institution(institution_data));

    $.each(json, function(i, val) {
        var institution_data;
        institution_data = {
            value: val.value,
            name: val.name,
            level: val.level,
            hasChildren: val.hasChildren,
            children: val.children,
            form: form_name
        };
        $('select.institution-select').append(ich.institution(institution_data));
    });
    refresh();
}

$('select.country-select').change(function() {
    $('select.institution-select').children().remove();
    $('.institution-select').select2('val', '');
    $('select.city-select').children().remove();
    $('.city-select').select2('val', '');
    if ($(this).val()) {
        $.ajax({
            type: 'GET',
            format: 'json',
            data: 'country_id=' + $(this).val(),
            url: Routing.generate('public_cities', {
                url: instance_url
            }),
            success: function(data) {
                loadCities(JSON.parse(data));
            }
        });
        $.ajax({
            type: 'GET',
            format: 'json',
            data: 'country_id=' + $(this).val() + '&filter=' + filter,
            url: Routing.generate('public_institutions_full', {
                'url': instance_url
            }),
            success: function(data) {
                loadInstitutions(JSON.parse(data));
            }
        });
    }
});

$('select.city-select').change(function() {
    $('select.institution-select').children().remove();
    $('.institution-select').select2('val', '');
    if ($(this).val()) {
        $.ajax({
            type: 'GET',
            data: 'city_id=' + $(this).val() + '&filter=' + filter,
            url: Routing.generate('public_institutions_full', {
                'url': instance_url
            }),
            success: function(data) {
                loadInstitutions(JSON.parse(data));
            }
        });
    }
});

$('.filter-select').change(function() {
    filter = $(this).val();
    if ($('select.city-select').val() !== '') {
        $('select.city-select').change();
    } else if ($('select.country-select').val() !== '') {
        $('select.country-select').change();
    }
});

$("#celsius_corebundle_baseusertype_instance").select2();
$(".country-select").select2();
$(".city-select").select2();
$(".institution-select").select2();

/*
 * Cuando la institucion ya viene cargada en el form, es necesario recargar
 * los option de .institution-select para que estos tengan el estilo correcto 
 */
var value = $('select.institution-select').val();
var done = false;
var filter = '';
var refresh = function() {
    if (!done) {
        done = true;
        $('.institution-select').select2('val', value);
    }
};
if (value !== '') {
    if ($('select.city-select').val() !== '') {
        $('select.city-select').change();
    } else if ($('select.country-select').val() !== '') {
        $('select.country-select').change();
    }
}