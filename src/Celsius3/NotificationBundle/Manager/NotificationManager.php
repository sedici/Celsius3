<?php

/*
 * Celsius3 - Order management
 * Copyright (C) 2014 PrEBi <info@prebi.unlp.edu.ar>
 *
 * This file is part of Celsius3.
 *
 * Celsius3 is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Celsius3 is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Celsius3.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Celsius3\NotificationBundle\Manager;

use Celsius3\NotificationBundle\Entity\Notification;
use Celsius3\MessageBundle\Entity\Message;
use Celsius3\CoreBundle\Entity\BaseUser;
use Celsius3\NotificationBundle\Entity\NotificationTemplate;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Celsius3\NotificationBundle\Entity\BaseUserNotification;
use Celsius3\NotificationBundle\Entity\MessageNotification;
use Celsius3\CoreBundle\Entity\Event\Event;
use Celsius3\NotificationBundle\Entity\EventNotification;

class NotificationManager
{

    const CAUSE__NEW_MESSAGE = 'new_message';
    const CAUSE__NEW_USER = 'new_user';
    const CAUSE__CREATE = 'creation';
    const CAUSE__SEARCH = 'search';
    const CAUSE__ANNUL = 'annul';
    const CAUSE__REQUEST = 'request';
    const CAUSE__RECEIVE = 'receive';
    const CAUSE__CANCEL = 'cancel';
    const CAUSE__DELIVER = 'deliver';

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
        $eventArray = array(
            'template_data' => function (Notification $notification) {
                return array(
                    'order' => $notification->getObject(),
                    'event' => $notification->getCause()
                );
            },
                    'route' => 'admin_order_show',
                    'route_params' => function (Notification $notification) {
                return array(
                    'id' => $notification->getObject()->getRequest()->getId()
                );
            }
                );

                return array(
                    self::CAUSE__NEW_MESSAGE => array(
                        'template_data' => function (Notification $notification) {
                            return array(
                                'user' => $notification->getObject()->getSender()
                            );
                        },
                                'route' => 'fos_message_thread_view',
                                'route_params' => function (Notification $notification) {
                            return array(
                                'threadId' => $notification->getObject()
                                        ->getThread()
                                        ->getId()
                            );
                        }
                            ),
                            self::CAUSE__NEW_USER => array(
                                'template_data' => function (Notification $notification) {
                                    return array(
                                        'user' => $notification->getObject()
                                    );
                                },
                                        'route' => 'admin_user',
                                        'route_params' => function (Notification $notification) {
                                    return array(
                                        'id' => $notification->getObject()->getId()
                                    );
                                }
                                    ),
                                    self::CAUSE__CREATE => $eventArray,
                                    self::CAUSE__SEARCH => $eventArray,
                                    self::CAUSE__ANNUL => $eventArray,
                                    self::CAUSE__REQUEST => $eventArray,
                                    self::CAUSE__RECEIVE => $eventArray,
                                    self::CAUSE__CANCEL => $eventArray,
                                    self::CAUSE__DELIVER => $eventArray
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
                                $context = new \ZMQContext();
                                $socket = $context->getSocket(\ZMQ::SOCKET_PUSH, 'notification pusher');
                                $socket->connect('tcp://' . $this->zmq_host . ':' . $this->zmq_port);

                                $socket->send(json_encode(array(
                                    'notification_id' => $notification->getId()
                                )));
                            }

                            private function notify(Notification $notification, $cause, $object, $receivers, NotificationTemplate $template)
                            {
                                $em = $this->container->get('doctrine.orm.entity_manager');
                                $notification->setCause($cause);
                                foreach ($receivers as $receiver) {
                                    $notification->addReceiver($receiver);
                                }
                                $notification->setObject($object);
                                $notification->setTemplate($template);
                                $em->persist($notification);
                                $em->flush($notification);

                                $this->notifyRatchet($notification);
                            }

                            public function notifyNewMessage(Message $message)
                            {
                                $receivers = new ArrayCollection($message->getThread()->getParticipants());
                                $em = $this->container->get('doctrine.orm.entity_manager');
                                $this->notify(new MessageNotification(), self::CAUSE__NEW_MESSAGE, $message, $receivers->filter(function (BaseUser $receiver) use ($message) {
                                            return ($receiver->getId() != $message->getSender()->getId());
                                        }), $em->getRepository('Celsius3NotificationBundle:NotificationTemplate')
                                                ->findOneBy(array(
                                                    'code' => self::CAUSE__NEW_MESSAGE
                                )));
                            }

                            public function notifyNewUser(BaseUser $user)
                            {
                                $em = $this->container->get('doctrine.orm.entity_manager');
                                $admins = $em->getRepository('Celsius3CoreBundle:BaseUser')->findAdmins($user->getInstance());
                                $this->notify(new BaseUserNotification(), self::CAUSE__NEW_USER, $user, $admins, $em->getRepository('Celsius3NotificationBundle:NotificationTemplate')
                                                ->findOneBy(array(
                                                    'code' => self::CAUSE__NEW_USER
                                )));
                            }

                            public function notifyEvent(Event $event, $type)
                            {
                                $em = $this->container->get('doctrine.orm.entity_manager');

                                $users = $em->createQueryBuilder()
                                                ->select('u')
                                                ->from('Celsius3CoreBundle:BaseUser', 'u')
                                                ->join('u.notificationSettings', 'ns')
                                                ->where('ns.type = :type')->setParameter('type', $type . '_notification')
                                                ->andWhere('ns.instance = :instance')->setParameter('instance', $this->container->get('celsius3_core.instance_helper')->getSessionInstance())
                                                ->andWhere('ns.subscribedToInterfaceNotifications = :uin')->setParameter('uin', TRUE)
                                                ->getQuery()->execute();

                                foreach ($users as $user) {
                                    $this->notify(new EventNotification(), $type, $event, array($user), $em->getRepository('Celsius3NotificationBundle:NotificationTemplate')
                                                    ->findOneBy(array('code' => 'event_template')));
                                }
                            }

                            public function getUnreadNotificationsCount($user_id)
                            {
                                $em = $this->container->get('doctrine.orm.entity_manager');

                                return $em->getRepository('Celsius3NotificationBundle:Notification')->getUnreadNotificationsCount($user_id);
                            }

                            public function getUnreadNotifications($user_id)
                            {
                                $em = $this->container->get('doctrine.orm.entity_manager');

                                return $em->getRepository('Celsius3NotificationBundle:Notification')->getUnreadNotifications($user_id, $this->container->getParameter('notification_limit'));
                            }

                        }
                        