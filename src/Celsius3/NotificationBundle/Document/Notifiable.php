<?php
namespace Celsius3\NotificationBundle\Document;
use Celsius3\NotificationBundle\Manager\NotificationManager;

interface Notifiable
{
    public function notify(NotificationManager $manager);
}
