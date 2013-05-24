<?php

namespace Celsius\Celsius3Bundle\Manager;

class NotificationManager
{
    static function getCauseNotification()
    {
        return $CAUSE__ARRAY__NOTIFICATION = array( 'CAUSE__NEW__MESSAGE' => 'NewMessage',
                                                    'CAUSE__NEW__USER' => 'NewUser');
    }
  //  static function notifyUser()
  //  {
 /*   ?>
    <script>
        var myClank = Clank.connect("ws://localhost:8080");
        myClank.on("socket/connect", function(session){
            //session is an Autobahn JS WAMP session.
            console.log("Successfully Connected!");
            session.publish("channel/517125eb3da6ba480c0000ca", {msg: "Notificaiones!"});
        })
        alert(2);
    </script>
    <?php
  * 
  */
   // }
}