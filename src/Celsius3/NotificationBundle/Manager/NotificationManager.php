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
    private $zmq_port;
    private $zmq_host;

    public function __construct(ContainerInterface $container, $zmq_host,
            $zmq_port)
    {
        $this->container = $container;
        $this->zmq_host = $zmq_host;
        $this->zmq_port = $zmq_port;
    }

    private function notifyRatchet(Notification $notification, $cause)
    {
        $entryData = array('id' => $notification->getObject()->getId(),
                'cause' => $cause,
                'user_ids' => array_map(
                        function ($receiver)
                        {
                            return $receiver->getId();
                        }, $notification->getReceivers()->toArray()),);

        // This is our new stuff
        $context = new \ZMQContext();
        $socket = $context->getSocket(\ZMQ::SOCKET_PUSH, 'notification pusher');
        $socket->connect('tcp://' . $this->zmq_host . ':' . $this->zmq_port);

        $socket->send(json_encode($entryData));
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

        $this->notifyRatchet($notification, $cause);
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
