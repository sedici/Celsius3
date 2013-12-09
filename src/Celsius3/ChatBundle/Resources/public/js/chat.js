ChatRoom = function(optDebug) {
    var onError = function(error) {
        Debug('Error: ' + error);
    }

    var Debug = function(msg) {
        if (api.debug) {
            console.log(msg);
        }
    }

    var api = {
        events: [
            /**
             * The user has connected to the server
             * @event connected
             */
            'connect'

                    /**
                     * The user has disconnected from the server
                     * @event disconnect
                     */
                    , 'disconnect'

                    /**
                     * Crap crap crap!
                     * @event error
                     * @param string Message from the server
                     */
                    , 'error'

                    /**
                     * Another use has joined a room the current user is in
                     * @event joinRoom
                     * @param string Room name
                     * @param string Unique ID of the person who joined
                     * @param string Display name of the person who joined (make sure to store this in a lookup)
                     */
                    , 'joinRoom'

                    /**
                     * Another use has left one of the rooms this user is in
                     * @event leftRoom
                     * @param string Room name
                     * @param string Unique ID of the person who left
                     */
                    , 'leftRoom'

                    /**
                     * A message has been received in one of the chat rooms
                     * @event message
                     * @param string Room the message is sent to
                     * @param string Unique ID of the person who sent the message
                     * @param string Message received
                     */
                    , 'message'
        ]

                , debug: optDebug | false

                , setName: function(name) {
            // Name can not be longer than 32 characters

            sess.call('setName', name).then(function() {
            }, onError);
        }

        , join: function(room) {
            // check room name is clean
            // No more than 32 characters
            // Must be alpha numeric

            sess.subscribe(room, function(room, msg) {
                var action = msg.shift();
                msg.unshift(room);

                Debug([action, msg]);

                $(api).trigger(action, msg);
            });
        }

        , leave: function(room) {
            sess.unsubscribe(room);
        }

        , send: function(room, msg) {
            // Message can not be longer than 140 characters

            sess.publish(room, msg);
        }

        , end: function() {
            sess.close();
        }

        , create: function(name, callback) {
            sess.subscribe(name, function(room, msg) {
                callback(room);
            });
        }

        , sessionId: ''

                , rooms: {}
    }

    ab._debugrpc = api.debug;
    ab._debugpubsub = api.debug;
    ab._debugws = api.debug;

    var sess = new ab.Session(
            'ws://' + chat_host + ':' + chat_port
            , function() {
        api.sessionId = sess._session_id;

        Debug('Connected! ' + api.sessionId);

        sess.subscribe(hive_id, function(room, msg) {
            Debug('ctrl:rooms: ' + msg);
            var state = msg.pop();

            console.log(room);
            console.log(msg);
            console.log(state);

            if (1 == state) {
                api.rooms[msg[0]] = msg[1];
                $(api).trigger('openRoom', msg);
            } else {
                delete api.rooms[msg[0]];
                $(api).trigger('closeRoom', msg);
            }
        });

        $(api).trigger('connect');
    }
    , function() {
        Debug('Connection closed');
        $(api).trigger('close');
    }
    , {
        'skipSubprotocolCheck': true
    }
    );

    return api;
};

var GUI = function() {
    var Chat;
    var focusRoom = hive_id;

    var Joined = [];
    var Names = {};

    function createAccordian(room) {
        var roomName = Chat.rooms[room];

        $('<a href="#" class="groupHead" data-channel="' + room + '">' + roomName + '<span class="notifications none">0</span></a><ul class="users"></ul>').appendTo('.rooms');
        $('<div id="' + room + '" class="chatWindow"></div>').prependTo('#colB');
    }

    function createTab(room, roomName) {
        $('<li data-channel="' + room + '"><a href="#">' + roomName + '<span>Join</span></a></li>').hide().prependTo('#channelList ul').fadeIn('slow');
    }

    var status = function() {
        var btnStatus;

        return {
            init: function() {
                btnStatus = $('#chat-status');
            }

            , update: function(status) {
                btnStatus.removeClass().addClass('btn');

                switch (status) {
                    case 'online':
                        btnStatus.addClass('btn-success').html('Online');
                        break;
                    case 'connecting':
                        btnStatus.addClass('btn-warning').html('Connecting');
                        break;
                    case 'offline':
                        btnStatus.addClass('btn-inverse').html('Offline');
                        break;
                    case 'error':
                        btnStatus.addClass('btn-danger').html('Offline');
                        break;
                }
            }
        }
    }();

    function focusChannel(channel) {
        var objAccordian = $('.groupHead[data-channel="' + channel + '"]');

        $('.groupHead').each(function() {
            $('.open').next('.users').slideUp();
            $('.open').removeClass('open');
        });

        $(objAccordian).next('.users').slideToggle();
        $(objAccordian).toggleClass('open');

        $('#chat .active').each(function() {
            $(this).fadeOut();
            $(this).removeClass('active');
        });

        $('#' + channel).fadeIn();
        $('#' + channel).addClass('active');
        focusRoom = channel;
        $('#textbox input').focus();
        objAccordian.children('.notifications').addClass('none').html(0);
    }

    function join(id) {
        if (-1 !== $.inArray(id, Joined)) {
            return false;
        }

        $('li[data-channel="' + id + '"]').addClass('joined');
        $('li[data-channel="' + id + '"] span').html('Leave');

        Chat.join(id);
        createAccordian(id);
        focusChannel(id);

        Joined.push(id);
    }

    function leave(room) {
        $('li[data-channel="' + room + '"]').removeClass('joined');
        $('li[data-channel="' + room + '"] span').html('Join');

        Chat.leave(room);

        $('#' + room).fadeOut('fast', function() {
            $(this).remove();
        });
        $('.groupHead[data-channel="' + room + '"]').next('.users').fadeOut('fast', function() {
            $(this).remove();
        });
        $('.groupHead[data-channel="' + room + '"]').fadeOut('fast', function() {
            $(this).remove()
        });

        delete Joined[$.inArray(room, Joined)];
    }

    $(function() {
        var listWidth = 0;

        $(document).on('.groupHead', 'click', function() {
            focusChannel($(this).data('channel'));

            return false;
        });

        $(document).on('#channelList ul li a', 'click', function() {
            roomId = $(this).parent('li').data('channel');

            if (-1 != $.inArray(roomId, Joined)) {
                leave(roomId);
            } else {
                join(roomId);
            }

            return false;
        });

        $('#channelList ul li').each(function() {
            listWidth = (listWidth + $(this).width()) + 15;
            $('#channelList ul').width(listWidth);
        });

        $('#textbox').submit(function() {
            var text = $('#textbox input').val();
            Chat.send(focusRoom, text);
            $('#textbox input').val('');
            return false;
        });

        status.init();
        status.update('connecting');
        $.cookie('name', username, {domain: window.location.hostname});

        Chat = new ChatRoom();

        $(Chat).bind('connect', function(e) {
            Names[Chat.sessionId] = 'Me';

            status.update('online');

            Chat.create(hive_id, function(id, display) {
                join(id);
            });
        });

        $(Chat).bind('close', function(e) {
            status.update('error');
        });

        $(Chat).bind('message', function(e, room, from, msg, time) {
            console.log(room);
            console.log(from);
            console.log(msg);
            console.log(time);
            if (focusRoom != room) {
                var number = $('.groupHead[data-channel="' + room + '"] .notifications').html();
                number = parseInt(number) + 1;
                $('.groupHead[data-channel="' + room + '"] .notifications').html(number).removeClass('none');
                // update counter
            }

            // create div, put in box
            var isMine = (Chat.sessionId == from ? ' mine' : '');
            $('<div class="comment' + isMine + '"><h2>' + Names[from] + '<br /><span class="timeago" title="' + time + '">' + time + '</span></h2><p>' + msg + '</p></div>').hide().prependTo('#' + room).fadeIn('slow');
            $('.timeago').removeClass('timeago').timeago();
        });

        $(Chat).bind('openRoom', function(e, roomId, roomName) {
            createTab(roomId, roomName);
        });

        $(Chat).bind('closeRoom', function(e, room) {
            $('#' + room).fadeOut('fast', function() {
                $(this).remove();
            });
            $('.groupHead[data-channel="' + room + '"]').next('.users').fadeOut('fast', function() {
                $(this).remove();
            });
            $('.groupHead[data-channel="' + room + '"]').fadeOut('fast', function() {
                $(this).remove();
            });
            $('#channelList ul li[data-channel="' + room + '"]').fadeOut('slow', function() {
                $(this).remove();
            });
        });

        $(Chat).bind('leftRoom', function(e, room, id) {
            // name has left room
            $('#' + id + room).remove();
        });

        $(Chat).bind('joinRoom', function(e, room, id, name) {
            Names[id] = name;
            $('<li id="' + id + room + '"><span>Indicator</span>' + name + '</li>').appendTo($('.groupHead[data-channel="' + room + '"]').next('.users'));
        });
    });
}();