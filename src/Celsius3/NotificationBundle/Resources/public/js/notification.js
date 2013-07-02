if (user_id != '') {
	var conn = new ab.Session('ws://' + notification_host + ':'
			+ notification_port, function() {
		conn.subscribe(user_id, function(topic, data) {
			// This is where you would add the new article to the DOM
			// (beyond the
			// scope of this tutorial)
			console.log('New notification "' + topic + '" : ' + data.id);
		});
	}, function() { // When the connection is closed
		console.warn('WebSocket connection closed');
	}, { // Additional parameters, we're ignoring the WAMP sub-protocol
		// for older
		// browsers
		'skipSubprotocolCheck' : true
	});
}