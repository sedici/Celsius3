<?php

namespace Celsius3\NotificationBundle\Manager;
use Celsius3\NotificationBundle\Document\Notification;
use Celsius3\MessageBundle\Document\Message;
use Doctrine\ODM\MongoDB\DocumentManager;
use Celsius3\CoreBundle\Document\BaseUser;

class NotificationManager
{
    const CAUSE__NEW_MESSAGE = 'new_message';
    const CAUSE__NEW_USER = 'new_user';

    private $dm;

    public function __construct(DocumentManager $dm)
    {
        $this->dm = $dm;
    }

    private function notifyNewMessage(Message $message)
    {
        foreach ($message->getThread()->getParticipants() as $participant)
        {
            $notification = new Notification();
            $notification->setCause(self::CAUSE__NEW_MESSAGE);
            $notification->setReceiver($participant);
            $notification->setObject($message);
            $this->dm->persist($notification);
        }
        $this->dm->flush();
    }

    private function notifyNewUser(BaseUser $user)
    {

    }

    public function notify($cause, $object)
    {
        switch ($cause) {
        case self::CAUSE__NEW_MESSAGE:
            return $this->notifyNewMessage($object);
            break;
        case self::CAUSE__NEW_USER:
            return $this->notifyNewUser($object);
            break;
        }
    }
}
