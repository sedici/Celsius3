function showNotification(data) {
	var notification_data;
	notification_data = {
		link : '#',
		template : data.id,
	};
	$('ul#notification-list').append(ich.notification(notification_data));
}

if (user_id != '') {
	var conn = new ab.Session('ws://' + notification_host + ':'
			+ notification_port, function() {
		conn.subscribe(user_id, function(topic, data) {
			//showNotification(data);
			console.log(data);
		});
	}, function() {
		console.warn('WebSocket connection closed');
	}, {
		'skipSubprotocolCheck' : true
	});
}