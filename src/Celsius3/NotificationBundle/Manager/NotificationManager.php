<?php

/*
 * Celsius3 - Order management
 * Copyright (C) 2014 PREBI-SEDICI <info@prebi.unlp.edu.ar> http://prebi.unlp.edu.ar http://sedici.unlp.edu.ar
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

use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Celsius3\NotificationBundle\Entity\BaseUserNotification;
use Celsius3\NotificationBundle\Entity\MessageNotification;
use Celsius3\NotificationBundle\Entity\EventNotification;
use Celsius3\NotificationBundle\Entity\Notification;
use Celsius3\CoreBundle\Entity\BaseUser;
use Celsius3\CoreBundle\Entity\Event\Event;
use Celsius3\MessageBundle\Entity\Message;
use JMS\TranslationBundle\Annotation\Ignore;

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
    const CAUSE__UPLOAD = 'upload';
    const CAUSE__RECLAIM = 'reclaim';

    private $container;
    private $zmq_port;
    private $zmq_host;

    public function __construct(ContainerInterface $container, $zmq_host, $zmq_port)
    {
        $this->container = $container;
        $this->zmq_host = $zmq_host;
        $this->zmq_port = $zmq_port;
    }

    private function getMap()
    {
        $translator = $this->container->get('translator');
        $eventArray = array(
            'template_data' => function (Notification $notification) use ($translator) {
                return array(
                    /** @Ignore */'request' => $notification->getObject()->getRequest(),
                    /** @Ignore */ 'event' => $translator->trans($notification->getCause()),
                );
            },
                    'route' => 'admin_order_show',
                    'route_params' => function (Notification $notification) {
                return array(
                    'id' => $notification->getObject()->getRequest()->getOrder()->getId()
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
                                    self::CAUSE__DELIVER => $eventArray,
                                    self::CAUSE__UPLOAD => $eventArray,
                                    self::CAUSE__RECLAIM => $eventArray,
                                );
                            }

                            public function getRenderedTemplate(Notification $notification)
                            {
                                $data = $this->getMap()[$notification->getCause()]['template_data'];

                                $template = $this->container->get('twig')->createTemplate($notification->getTemplate()->getText());
                                return $template->render($data($notification));
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
                                    'type' => 'notification',
                                    'data' => array('notification_id' => $notification->getId())
                                )));

                                $socket->disconnect('tcp://' . $this->zmq_host . ':' . $this->zmq_port);
                            }

                            private function notifyInterface(Notification $notification, $receivers)
                            {
                                $em = $this->container->get('doctrine.orm.entity_manager');

                                foreach ($receivers as $receiver) {
                                    $notification->addReceiver($receiver);
                                }

                                $em->persist($notification);
                                $em->flush($notification);

                                $this->notifyRatchet($notification);
                            }

                            private function notifyEmail(Notification $notification, $receivers, $instance, $otherText = '')
                            {
                                $mailer = $this->container->get('celsius3_core.mailer');
                                $translator = $this->container->get('translator');

                                $function = $this->getMap()[$notification->getCause()]['template_data'];
                                $data = $function($notification);

                                $template = $this->container->get('twig')->createTemplate($notification->getTemplate()->getText());

                                foreach ($receivers as $user) {
                                    $text = 'Celsius3 - ' . $user->getInstance();
                                    $text .= "\n\n";
                                    $text .= $template->render($data) . ' ';
                                    $text .= $otherText;

                                    if (/** @Ignore */!$user->getWrongEmail()) {
                                        $mailer->sendEmail($user->getEmail(), 'Celsius 3 ' . $translator->trans(/** @Ignore */$notification->getCause()), $text, $instance);
                                    }
                                }
                            }

                            public function notifyNewMessage(Message $message)
                            {
                                $receivers = new ArrayCollection($message->getThread()->getParticipants());
                                $em = $this->container->get('doctrine.orm.entity_manager');

                                $usersInterfaceNotification = $em->getRepository('Celsius3CoreBundle:BaseUser')->getUsersWithMessageNotification('interface', $receivers);
                                $usersEmailNotification = $em->getRepository('Celsius3CoreBundle:BaseUser')->getUsersWithMessageNotification('email', $receivers);

                                $receiversInterfaceNotification = $receivers->filter(function (BaseUser $receiver) use ($message, $usersInterfaceNotification) {
                                    return ($receiver->getId() != $message->getSender()->getId()) && (in_array($receiver, $usersInterfaceNotification));
                                });
                                $receiversEmailNotification = $receivers->filter(function (BaseUser $receiver) use ($message, $usersEmailNotification) {
                                    return ($receiver->getId() != $message->getSender()->getId()) && (in_array($receiver, $usersEmailNotification));
                                });

                                $template = $em->getRepository('Celsius3NotificationBundle:NotificationTemplate')
                                        ->findOneBy(array('code' => self::CAUSE__NEW_MESSAGE));

                                $notification = new MessageNotification(self::CAUSE__NEW_MESSAGE, $message, $template);

                                $this->notifyInterface($notification, $receiversInterfaceNotification);
                                $this->notifyEmail($notification, $receiversEmailNotification, $message->getSender()->getInstance());
                            }

                            public function notifyNewUser(BaseUser $user)
                            {
                                $em = $this->container->get('doctrine.orm.entity_manager');

                                $adminsInterfaceNotification = $em->getRepository('Celsius3CoreBundle:BaseUser')->getAdminsWithUserNotification('interface', $user->getInstance());
                                $adminsEmailNotification = $em->getRepository('Celsius3CoreBundle:BaseUser')->getAdminsWithUserNotification('email', $user->getInstance());

                                $template = $em->getRepository('Celsius3NotificationBundle:NotificationTemplate')
                                        ->findOneBy(array('code' => self::CAUSE__NEW_USER));

                                $notification = new BaseUserNotification(self::CAUSE__NEW_USER, $user, $template);

                                $this->notifyInterface($notification, $adminsInterfaceNotification);
                                $this->notifyEmail($notification, $adminsEmailNotification, $user->getInstance());
                            }

                            public function notifyEvent(Event $event, $type)
                            {
                                $em = $this->container->get('doctrine.orm.entity_manager');
                                $router = $this->container->get('router');

                                $usersInsterfaceNotification = $em->getRepository('Celsius3CoreBundle:BaseUser')
                                        ->getUsersWithEventNotification('interface', $event, $type);
                                $usersEmailNotification = $em->getRepository('Celsius3CoreBundle:BaseUser')
                                        ->getUsersWithEventNotification('email', $event, $type);

                                $template = $em->getRepository('Celsius3NotificationBundle:NotificationTemplate')
                                        ->findOneBy(array('code' => 'order_event'));

                                $notification = new EventNotification($type, $event, $template);

                                foreach ($usersInsterfaceNotification as $user) {
                                    $this->notifyInterface($notification, array($user));
                                }

                                $otherText = "\n\nPara acceder al pedido ingrese al siguiente enlace.\n";
                                $otherText .= $router->generate('admin_order_show', array('id' => $notification->getObject()->getRequest()->getOrder()->getId()), true);

                                $this->notifyEmail($notification, $usersEmailNotification, $event->getInstance(), $otherText);
                            }

                            public function notifyRemoteEvent(Event $event, $type)
                            {
                                $em = $this->container->get('doctrine.orm.entity_manager');
                                $template = $em->getRepository('Celsius3NotificationBundle:NotificationTemplate')
                                        ->findOneBy(array('code' => 'order_event'));

                                $notification = new EventNotification($type, $event, $template);

                                $user = $event->getRemoteNotificationTarget();

                                if (!is_null($user)) {
                                    $this->notifyInterface($notification, array($user));
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
                        