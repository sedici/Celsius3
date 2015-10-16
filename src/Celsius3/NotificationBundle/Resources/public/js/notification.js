var Base64 =
{
    // private property
    _keyStr : "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",

    // public method for encoding
    encode : function (input)
    {
        var output = "";
        var chr1, chr2, chr3, enc1, enc2, enc3, enc4;
        var i = 0;

        input = Base64._utf8_encode(input);

        while (i < input.length) {

            chr1 = input.charCodeAt(i++);
            chr2 = input.charCodeAt(i++);
            chr3 = input.charCodeAt(i++);

            enc1 = chr1 >> 2;
            enc2 = ((chr1 & 3) << 4) | (chr2 >> 4);
            enc3 = ((chr2 & 15) << 2) | (chr3 >> 6);
            enc4 = chr3 & 63;

            if (isNaN(chr2)) {
                enc3 = enc4 = 64;
            } else if (isNaN(chr3)) {
                enc4 = 64;
            }

            output = output +
            this._keyStr.charAt(enc1) + this._keyStr.charAt(enc2) +
            this._keyStr.charAt(enc3) + this._keyStr.charAt(enc4);

        }

        return output;
    },

    // public method for decoding
    decode : function (input)
    {
        var output = "";
        var chr1, chr2, chr3;
        var enc1, enc2, enc3, enc4;
        var i = 0;

        input = input.replace(/[^A-Za-z0-9\+\/\=]/g, "");

        while (i < input.length) {

            enc1 = this._keyStr.indexOf(input.charAt(i++));
            enc2 = this._keyStr.indexOf(input.charAt(i++));
            enc3 = this._keyStr.indexOf(input.charAt(i++));
            enc4 = this._keyStr.indexOf(input.charAt(i++));

            chr1 = (enc1 << 2) | (enc2 >> 4);
            chr2 = ((enc2 & 15) << 4) | (enc3 >> 2);
            chr3 = ((enc3 & 3) << 6) | enc4;

            output = output + String.fromCharCode(chr1);

            if (enc3 != 64) {
                output = output + String.fromCharCode(chr2);
            }
            if (enc4 != 64) {
                output = output + String.fromCharCode(chr3);
            }

        }

        output = Base64._utf8_decode(output);

        return output;

    },

    // private method for UTF-8 encoding
    _utf8_encode : function (string)
    {
        string = string.replace(/\r\n/g,"\n");
        var utftext = "";

        for (var n = 0; n < string.length; n++) {

            var c = string.charCodeAt(n);

            if (c < 128) {
                utftext += String.fromCharCode(c);
            }
            else if((c > 127) && (c < 2048)) {
                utftext += String.fromCharCode((c >> 6) | 192);
                utftext += String.fromCharCode((c & 63) | 128);
            }
            else {
                utftext += String.fromCharCode((c >> 12) | 224);
                utftext += String.fromCharCode(((c >> 6) & 63) | 128);
                utftext += String.fromCharCode((c & 63) | 128);
            }

        }

        return utftext;
    },

    // private method for UTF-8 decoding
    _utf8_decode : function (utftext)
    {
        var string = "";
        var i = 0;
        var c = c1 = c2 = 0;

        while ( i < utftext.length ) {

            c = utftext.charCodeAt(i);

            if (c < 128) {
                string += String.fromCharCode(c);
                i++;
            }
            else if((c > 191) && (c < 224)) {
                c2 = utftext.charCodeAt(i+1);
                string += String.fromCharCode(((c & 31) << 6) | (c2 & 63));
                i += 2;
            }
            else {
                c2 = utftext.charCodeAt(i+1);
                c3 = utftext.charCodeAt(i+2);
                string += String.fromCharCode(((c & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
                i += 3;
            }

        }

        return string;

    }
};

var map = {
        notification: function (data) {
            $('span#notification-count').text(
                    parseInt($('span#notification-count').text()) +
                    parseInt(data.count));
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
                        html += '<p>' + Base64.decode(object.operator_username) + '</p>';
                    });

                    $(this).html(html);
                });

            });
        }
    };

    if (user_id !== '') {
        var conn = new ab.Session('ws://' + notification_host + ':' + notification_port, function () {
                    conn.subscribe('c3_user_' + user_id, function (topic, data) {
                        $(data).each(function (index, data) {
                            map.notification(data.data);
                        });
                    });
                    function register() {
                        if ($('div#operators-in-request').length > 0 && $('div#operators-in-request').attr('data-request-id') !== '') {
                            conn.subscribe('c3_request_' + $('div#operators-in-request').data('request-id') + '_' + user_id, function (topic, data) {
                                map.operator_in_request(data.data);
                            });
                        } else {
                            setTimeout(register, 1000);
                        }
                    }
                    register();
                }, function () {
            console.warn('WebSocket connection closed');
        }, {
            'skipSubprotocolCheck': true
        });
}
