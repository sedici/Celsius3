$(document).ready(function () {
    var map = {
        notification: function (data) {
            $('span#notification-count').text(
                    parseInt($('span#notification-count').text())
                    + parseInt(data.count));
            $(data.notifications).each(function (index, element) {
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
        },
        operator_in_request: function (data) {
            $(document).ready(function () {
                $('div#operators-in-request').each(function (index) {

                    var html = '';
                    $(data[$(this).attr('data-request-id')]).each(function (index, object) {
                        html += '<p>' + object.operator_fullname + '</p>';
                    });

                    $(this).html(html);
                });

            });
        }
    };

    if (user_id !== '') {
        var conn = new ab.Session('ws://' + notification_host + ':'
                + notification_port, function () {
                    conn.subscribe('c3_user_' + user_id, function (topic, data) {
                        $(data).each(function (index, data) {
                            map['notification'](data.data);
                        });
                    });
                    if (!_.isUndefined($('div#operators-in-request'))) {
                        if (!_.isEmpty($('div#operators-in-request').attr('data-request-id'))) {
                            conn.subscribe('c3_request_' + $('div#operators-in-request').attr('data-request-id'), function (topic, data) {
                                map['operator_in_request'](data.data);
                            });
                        }
                    }

                }, function () {
            console.warn('WebSocket connection closed');
        }, {
            'skipSubprotocolCheck': true
        });
    }
});