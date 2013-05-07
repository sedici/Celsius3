<?php

namespace Celsius\Celsius3Bundle\Manager;

class NotificationManager
{
    static function getCauseNotification()
    {
        return $CAUSE__ARRAY__NOTIFICATION = array( 'CAUSE__NEW__MESSAGE' => 'NewMessage',
                                                    'CAUSE__NEW__USER' => 'NewUser');
    }
}