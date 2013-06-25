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
	$.each(json, function(i, val) {
		var institution_data;

		institution_data = {
			value : val.value,
			name : val.name,
			hasChildren : val.hasChildren,
			form : form_name
		};

		$(parent).append(ich.institution(institution_data));
	});
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
					url : Routing.generate('public_institutions', {
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

$(document).on('click', '.expand-tree', function() {
	var element;
	element = $(this);
	$.ajax({
		type : 'GET',
		data : 'institution_id=' + $(this).parent().children('input').val(),
		url : Routing.generate('public_institutions', {
			'url' : instance_url
		}),
		success : function(data) {
			loadInstitutions(element.parent(), JSON.parse(data));
			element.removeClass('expand-tree');
			element.addClass('collapse-tree');
			element.text('-');
		}
	});
});

$(document).on('click', '.collapse-tree', function() {
	$(this).parent().children('p').remove();
	$(this).removeClass('collapse-tree');
	$(this).addClass('expand-tree');
	$(this).text('+');
});