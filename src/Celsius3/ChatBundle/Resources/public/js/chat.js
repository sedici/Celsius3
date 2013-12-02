function showChat(data) {
//    $('span#notification-count').text(
//            parseInt($('span#notification-count').text())
//            + parseInt(data.count));
//    $(data.notifications).each(function(index, element) {
//        var notification_data;
//        notification_data = {
//            link: Routing.generate('user_notification_view', {
//                id: element.id
//            }),
//            template: element.template
//        };
//        $('ul#notification-list').prepend(ich.notification(notification_data));
//        $('.notification').slice(notification_limit).remove();
//    });
}

if (user_id !== '') {
    var chat_conn = new ab.Session('ws://' + chat_host + ':'
            + chat_port, function() {
        chat_conn.subscribe(hive_id, function(topic, data) {
            showChat(data);
        });
    }, function() {
        console.warn('WebSocket connection closed');
    }, {
        'skipSubprotocolCheck': true
    });
}