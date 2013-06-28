

function loadCities(json) {
	$.each(json, function(i, val) {
		var city_data;

		city_data = {
			value : val.value,
			name : val.name
		};

		$('.city-select').append(ich.city(city_data));
	});
}

function loadInstitutions(parent, json) {
        $(parent).append('<select id="institutions"> </select>');
        $.each(json, function(i, val) {
		var institution_data;
		institution_data = {
			value : val.value,
			name : val.name,
			hasChildren : val.hasChildren,
                        children: val.children,
			form : form_name
		};
               ("#institutions").append(ich.city(institution_data));
	});
        $("#institutions").select2({placeholder: "Select a Institution",allowClear: true});
}

$('.country-select').change(
		function() {
                    	var institution_select = $(this).parents('.control-group').next()
					.next().children('.controls');
			$('.city-select > option:gt(0)').empty();
			institution_select.empty();
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
                                                loadInstitutions(institution_select, JSON.parse(data));
                                        }
				});
			}
		});

$('.city-select').change(
		function() {
			var institution_select = $(this).parents('.control-group').next()
					.children('.controls');
			institution_select.empty();
			if ($(this).val()) {
				$.ajax({
					type : 'GET',
					data : 'city_id=' + $(this).val(),
					url : Routing.generate('public_institutions', {
						'url' : instance_url
					}),
					success : function(data) {
						loadInstitutions(institution_select, JSON.parse(data));
					}
				});
			}
		});


$(document).ready(function() { 
    $("#celsius_corebundle_baseusertype_instance").select2({placeholder: "Select a Instance",allowClear: true});
    $(".country-select").select2({placeholder: "Select a Country",allowClear: true});
});
 