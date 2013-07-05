<?php
namespace Celsius3\NotificationBundle\Manager;

use Celsius3\NotificationBundle\Document\Notification;
use Celsius3\MessageBundle\Document\Message;
use Celsius3\CoreBundle\Document\BaseUser;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Celsius3\NotificationBundle\Document\NotificationTemplate;
use Symfony\Component\Routing\Router;

class NotificationManager
{

    const CAUSE__NEW_MESSAGE = 'new_message';

    const CAUSE__NEW_USER = 'new_user';

    private $container;

    private $zmq_port;

    private $zmq_host;

    private $templating;

    public function __construct(ContainerInterface $container, $zmq_host, $zmq_port)
    {
        $this->container = $container;
        $this->zmq_host = $zmq_host;
        $this->zmq_port = $zmq_port;
        $this->templating = new \Twig_Environment(new \Twig_Loader_String());
    }

    private function getMap()
    {
        return array(
            self::CAUSE__NEW_MESSAGE => array(
                'template_data' => function (Notification $notification)
                {
                    return array(
                        'user' => $notification->getObject()->getSender()
                    );
                },
                'route' => 'fos_message_thread_view',
                'route_params' => function (Notification $notification)
                {
                    return array(
                        'threadId' => $notification->getObject()
                            ->getThread()
                            ->getId()
                    );
                }
            ),
            self::CAUSE__NEW_USER => array(
                'template_data' => function (Notification $notification)
                {
                    return array(
                        'user' => $notification->getObject()
                    );
                },
                'route' => 'admin_user',
                'route_params' => function (Notification $notification)
                {
                    return array(
                        'id' => $notification->getObject()->getId()
                    );
                }
            )
        );
    }

    public function getRenderedTemplate(Notification $notification)
    {
        $data = $this->getMap()[$notification->getCause()]['template_data'];
        return $this->templating->render($notification->getTemplate()
            ->getText(), $data($notification));
    }

    public function generateUrl(Notification $notification)
    {
        $route = $this->getMap()[$notification->getCause()]['route'];
        $params = $this->getMap()[$notification->getCause()]['route_params'];
        return $this->container->get('router')->generate($route, $params($notification));
    }

    private function notifyRatchet(Notification $notification)
    {
        $entryData = array(
            'id' => $notification->getObject()->getId(),
            'cause' => $notification->getCause(),
            'user_ids' => array_map(function ($receiver)
            {
                return $receiver->getId();
            }, $notification->getReceivers()->toArray())
        );

        $context = new \ZMQContext();
        $socket = $context->getSocket(\ZMQ::SOCKET_PUSH, 'notification pusher');
        $socket->connect('tcp://' . $this->zmq_host . ':' . $this->zmq_port);

        $socket->send(json_encode(array(
            'notification_id' => $notification->getId()
        )));
    }

    private function notify($cause, $object, $receivers, NotificationTemplate $template)
    {
        $dm = $this->container->get('doctrine.odm.mongodb.document_manager');
        $notification = new Notification();
        $notification->setCause($cause);
        foreach ($receivers as $receiver) {
            $notification->addReceiver($receiver);
        }
        $notification->setObject($object);
        $notification->setTemplate($template);
        $dm->persist($notification);
        $dm->flush();

        $this->notifyRatchet($notification);
    }

    public function notifyNewMessage(Message $message)
    {
        $receivers = new ArrayCollection($message->getThread()->getParticipants());
        $dm = $this->container->get('doctrine.odm.mongodb.document_manager');
        $this->notify(self::CAUSE__NEW_MESSAGE, $message, $receivers->filter(function ($receiver) use($message)
        {
            return ($receiver->getId() != $message->getSender()
                ->getId());
        }), $dm->getRepository('Celsius3NotificationBundle:NotificationTemplate')
            ->findOneBy(array(
            'code' => self::CAUSE__NEW_MESSAGE
        )));
    }

    public function notifyNewUser(BaseUser $user)
    {
        $dm = $this->container->get('doctrine.odm.mongodb.document_manager');
        $admins = $dm->getRepository('Celsius3CoreBundle:BaseUser')->findAdmins($user->getInstance());
        $this->notify(self::CAUSE__NEW_USER, $user, $admins, $dm->getRepository('Celsius3NotificationBundle:NotificationTemplate')
            ->findOneBy(array(
            'code' => self::CAUSE__NEW_USER
        )));
    }

    public function getUnreadNotificationsCount($user_id)
    {
        $dm = $this->container->get('doctrine.odm.mongodb.document_manager');
        return $dm->getRepository('Celsius3NotificationBundle:Notification')->getUnreadNotificationsCount($user_id);
    }

    public function getUnreadNotifications($user_id)
    {
        $dm = $this->container->get('doctrine.odm.mongodb.document_manager');
        return $dm->getRepository('Celsius3NotificationBundle:Notification')->getUnreadNotifications($user_id, $this->container->getParameter('notification_limit'));
    }
}
