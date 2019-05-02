function first_upper (text) {
    if ((typeof text) !== 'string') {
        return text;
    }

    var words = text.split(" ");

    var t = '';
    for (var i = 0; i < words.length; i++) {
        if (words[i].length > 3 || i === 0) {
            t += words[i].charAt(0).toUpperCase() + words[i].substr(1).toLowerCase() + ' ';
        } else {
            t += words[i].toLowerCase() + ' ';
        }
    }

    return t;
};

function loadCities(json) {
    var city_data;
  
    city_data = {
        value: '',
        name: ''
    };
    var ci = function(city_data){ return "<option value='"+city_data.value+"'>"+first_upper(city_data.name)+"</option>"};

    
    
    $('select.city-select').append(ci(city_data));

    $.each(json, function (i, val) {
        var city_data;
        city_data = {
            value: val.value,
            name: val.name
        };
        $('select.city-select').append(ci(city_data));
    });
}

function loadInstitutions(json) {
    var institution_data;
    institution_data = {
        value: '',
        name: '',
        level: 1,
        hasChildren: false,
        children: []
    };
    var ins = function(institution_data){ return "<option value='"+institution_data.value+"'>"+institution_data.name+"</option>"};
    
    function recursiveInstitutionPrint(institution_data){
        $('select.institution-select').append("<option value='"+institution_data.value+"'>"+institution_data.name+"</option>");
        if (institution_data['hasChildren']){
            institution_data['children'].forEach(recursiveInstitutionPrint);
        }
    
    }

    $('select.institution-select').append(ins(institution_data));
    $.each(json, function (i, val) {
        var institution_data;
        institution_data = {
            value: val.value,
            name: val.name,
            level: val.level,
            hasChildren: val.hasChildren,
            children: val.children
        };
        
      
        recursiveInstitutionPrint(institution_data);
    });
    refresh();
}

$(document).on('change', 'select.country-select', function () {
    $('select.institution-select').children().remove();
    $('.institution-select').select2('val', '');
    $('select.city-select').children().remove();
    $('.city-select').select2('val', '');
    if ($(this).val()) {
        $.ajax({
            type: 'GET',
            format: 'json',
            data: 'country_id=' + $(this).val(),
            url: Routing.generate('public_cities'),
            success: function (data) {
               
                loadCities(JSON.parse(data));
            }
        });
        $.ajax({
            type: 'GET',
            format: 'json',
            data: 'country_id=' + $(this).val() + '&filter=' + filter,
            url: Routing.generate('public_institutions_full'),
            success: function (data) {
              
                loadInstitutions(JSON.parse(data));
            }
        });
    }
});

$(document).on('change', 'select.city-select', function () {
    
    $('select.institution-select').children().remove();
   // $('.institution-select').select2('val', '');
    if ($(this).val()) {
        $.ajax({
            type: 'GET',
            data: 'city_id=' + $(this).val() + '&filter=' + filter,
            url: Routing.generate('public_institutions_full'),
            success: function (data) {
                
                loadInstitutions(JSON.parse(data));
            }
            
        });
    }
});

$(document).on('change', '.filter-select', function () {
    filter = $(this).val();
    if ($('select.city-select').val() !== '') {
        $('select.city-select').change();
    } else if ($('select.country-select').val() !== '') {
        $('select.country-select').change();
    }
});

$("#baseuser_instance").select2();
$(".country-select").select2({
    allowClear: true,});
$(".city-select").select2({ allowClear: true,});
$(".institution-select").select2({ allowClear: true,});

/*
 * Cuando la institucion ya viene cargada en el form, es necesario recargar
 * los option de .institution-select para que estos tengan el estilo correcto
 */
var value = $('select.institution-select').val();
var done = false;
var filter = '';
var refresh = function () {
    if (!done) {
        done = true;
        $('.institution-select').val(value);
    }
};
if (value !== '') {
    if ($('select.city-select').val() !== '') {
        $('select.city-select').change();
    } else if ($('select.country-select').val() !== '') {
        $('select.country-select').change();
    }
}
