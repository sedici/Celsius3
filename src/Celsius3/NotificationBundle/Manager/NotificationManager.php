<?php

namespace Celsius3\NotificationBundle\Manager;
use Celsius3\NotificationBundle\Document\Notification;
use Celsius3\MessageBundle\Document\Message;
use Celsius3\CoreBundle\Document\BaseUser;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\Collections\ArrayCollection;

class NotificationManager
{
    const CAUSE__NEW_MESSAGE = 'new_message';
    const CAUSE__NEW_USER = 'new_user';

    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    private function notify($cause, $object, $receivers)
    {
        $dm = $this->container->get('doctrine.odm.mongodb.document_manager');
        $notification = new Notification();
        $notification->setCause($cause);
        foreach ($receivers as $receiver) {
            $notification->addReceiver($receiver);
        }
        $notification->setObject($object);
        $dm->persist($notification);
        $dm->flush();
    }

    public function notifyNewMessage(Message $message)
    {
        $receivers = new ArrayCollection(
                $message->getThread()->getParticipants());
        $this
                ->notify(self::CAUSE__NEW_MESSAGE, $message,
                        $receivers
                                ->filter(
                                        function ($receiver) use ($message)
                                        {
                                            return ($receiver->getId()
                                                    != $message->getSender()
                                                            ->getId());
                                        }));
    }

    public function notifyNewUser(BaseUser $user)
    {
        $dm = $this->container->get('doctrine.odm.mongodb.document_manager');
        $admins = $dm->getRepository('Celsius3CoreBundle:BaseUser')
                ->findAdmins($user->getInstance());
        $this->notify(self::CAUSE__NEW_USER, $user, $admins);
    }
}
