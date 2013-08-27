function showNotifications(data) {
    $('span#notification-count').text(
            parseInt($('span#notification-count').text())
            + parseInt(data.count));
    $(data.notifications).each(function(index, element) {
        var notification_data;
        notification_data = {
            link: Routing.generate('user_notification_view', {
                id: element.id
            }),
            template: element.template
        };
        $('ul#notification-list').prepend(ich.notification(notification_data));
        $('.notification').slice(notification_limit).remove();
    });
}

if (user_id != '') {
    var conn = new ab.Session('ws://' + notification_host + ':'
            + notification_port, function() {
        conn.subscribe(user_id, function(topic, data) {
            showNotifications(data);
        });
    }, function() {
        console.warn('WebSocket connection closed');
    }, {
        'skipSubprotocolCheck': true
    });
}