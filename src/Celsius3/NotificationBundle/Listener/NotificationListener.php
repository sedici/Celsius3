<?php

namespace Celsius3\NotificationBundle\Listener;
use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use Celsius3\NotificationBundle\Manager\NotificationManager;
use Celsius3\NotificationBundle\Document\Notifiable;

class NotificationListener
{
    private $notification_manager;

    public function __construct(NotificationManager $notification_manager)
    {
        $this->notification_manager = $notification_manager;
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $document = $args->getDocument();

        if ($document instanceof Notifiable) {
            $document->notify($this->notification_manager);
        }
    }
}
