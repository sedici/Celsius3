function loadCities(json) {
	$.each(json, function(i, val) {
		var city_data;

		city_data = {
			value : val.value,
			name : val.name
		};

		$('select.city-select').append(ich.city(city_data));
	});
}

function loadInstitutions(json) {
        $('select.country-select').append('<img id="loading" src="bundles/celsius3core/images/loading.gif"/>');

	$.each(json, function(i, val) {
		var institution_data;
		institution_data = {
			value : val.value,
			name : val.name,
			hasChildren : val.hasChildren,
			children : val.children,
			form : form_name
		};
		$('select.institution-select').append(ich.institution(institution_data));
	});

}

$('select.country-select').change(function() {
        $('select.institution-select').children().remove();
        $('select.city-select').children().remove();
       
        if ($(this).val()) {
		$.ajax({
			type : 'GET',
			format : 'json',
			data : 'country_id=' + $(this).val(),
			url : Routing.generate('public_cities', {
				url : instance_url
			}),
			success : function(data) {
				loadCities(JSON.parse(data));
			}
		});
		$.ajax({
			type : 'GET',
			format : 'json',
			data : 'country_id=' + $(this).val(),
			url : Routing.generate('public_institutions_full', {
				'url' : instance_url
			}),
			success : function(data) {
				loadInstitutions(JSON.parse(data));
			}
		});
	}
});

$('select.city-select').change(function() {
	$('.institution-select > option:gt(0)').empty();
	if ($(this).val()) {
		$.ajax({
			type : 'GET',
			data : 'city_id=' + $(this).val(),
			url : Routing.generate('public_institutions', {
				'url' : instance_url
			}),
			success : function(data) {
				loadInstitutions(JSON.parse(data));
			}
		});
	}
});

$(document).ready(function() {
	$("#celsius_corebundle_baseusertype_instance").select2({
		placeholder : "Select a Instance",
		allowClear : true
	});
	$(".country-select").select2({
		placeholder : "Select a Country",
		allowClear : true
	});
	$(".city-select").select2({
		placeholder : "Select a City",
		allowClear : true
	});
	$(".institution-select").select2({
		placeholder : "Select a Institution",
		allowClear : true
	});
});
