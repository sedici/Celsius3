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

namespace Celsius3\NotificationBundle\Server;

use Ratchet\ConnectionInterface;
use Ratchet\Wamp\WampServerInterface;
use Celsius3\NotificationBundle\Manager\NotificationManager;
use Doctrine\ORM\EntityManager;
use Celsius3\CoreBundle\Helper\InstanceHelper;

class Pusher implements WampServerInterface
{

    /**
     * A lookup of all the topics clients have subscribed to
     */
    private $subscribedTopics = array();
    private $connections = array();
    private $connectionsByOperatorInRequest = array();
    private $notificationManager;
    private $entityManager;
    private $instanceHelper;

    public function __construct(NotificationManager $notificationManager, EntityManager $entityManager, InstanceHelper $instanceHelper)
    {
        $this->notificationManager = $notificationManager;
        $this->entityManager = $entityManager;
        $this->instanceHelper = $instanceHelper;
    }

    private function getNotificationData($count, $notifications)
    {
        $data = array(
            'count' => $count,
            'notifications' => array(),
        );

        foreach ($notifications as $notification) {
            $data['notifications'][] = array(
                'template' => $this->notificationManager->getRenderedTemplate($notification),
                'id' => $notification->getId(),
            );
        }

        return $data;
    }

    private function getOperatorData($request)
    {
        $operatorsRequests = $this->entityManager->getRepository('Celsius3NotificationBundle:OperatorInRequest')
                ->findBy(array(
            'request' => $request,
            'working' => true
        ));

        $data = array();
        foreach ($operatorsRequests as $or) {
            $data[$or->getRequest()->getId()][] = array(
                'operator_id' => $or->getOperator()->getId(),
                'operator_fullname' => $or->getOperator()->__toString()
            );
        }

        return $data;
    }

    public function onSubscribe(ConnectionInterface $conn, $topic)
    {
        try {
            $map = array(
                'user' => function($conn, $topic, $id) {
                    $this->connections[$conn->resourceId]['user'] = $topic;
                    $notificationData = $this->getNotificationData($this->notificationManager->getUnreadNotificationsCount($id), array_reverse($this->notificationManager->getUnreadNotifications($id)));

                    return array(
                        'type' => 'notification',
                        'data' => $notificationData
                    );
                },
                        'request' => function($conn, $topic, $id) {
                    $this->connections[$conn->resourceId]['request'] = $topic;

                    if (!isset($this->connectionsByOperatorInRequest[$this->connections[$conn->resourceId]['user']->getId()][$topic->getId()])) {
                        $this->connectionsByOperatorInRequest[$this->connections[$conn->resourceId]['user']->getId()][$topic->getId()] = 0;
                    }
                    $this->connectionsByOperatorInRequest[$this->connections[$conn->resourceId]['user']->getId()][$topic->getId()] ++;

                    $request = $this->entityManager->getRepository('Celsius3CoreBundle:Request')->find($id);

                    $operatorData = $this->getOperatorData($request);

                    return array(
                        'type' => 'operator_in_request',
                        'data' => $operatorData
                    );
                }
                    );

                    $topicArray = explode('_', $topic->getId());
                    $data = $map[$topicArray[1]]($conn, $topic, $topicArray[2]);

                    if (!array_key_exists($topic->getId(), $this->subscribedTopics)) {
                        $this->subscribedTopics[$topic->getId()] = $topic;
                        echo $topic->getId() . "\n";
                    }

                    $topic->broadcast($data);
                } catch (\Exception $e) {
                    echo $e->getMessage();
                }
            }

            public function onUnSubscribe(ConnectionInterface $conn, $topic)
            {
                echo "Unsubscribe \n";
            }

            public function onOpen(ConnectionInterface $conn)
            {
                
            }

            public function onClose(ConnectionInterface $conn)
            {
                if (!array_key_exists($conn->resourceId, $this->connections)) {
                    return;
                }

                $connTopics = $this->connections[$conn->resourceId];

                if (isset($this->connectionsByOperatorInRequest[$connTopics['user']->getId()][$connTopics['request']->getId()])) {
                    if (intval($this->connectionsByOperatorInRequest[$connTopics['user']->getId()][$connTopics['request']->getId()]) <= 1) {

                        if (array_key_exists('request', $connTopics)) {
                            $user = $this->entityManager
                                    ->getRepository('Celsius3CoreBundle:BaseUser')
                                    ->find(explode('_', $connTopics['user']->getId())[2]);
                            $request = $this->entityManager
                                    ->getRepository('Celsius3CoreBundle:Request')
                                    ->find(explode('_', $connTopics['request']->getId())[2]);

                            $or = $this->entityManager
                                    ->getRepository('Celsius3NotificationBundle:OperatorInRequest')
                                    ->findOneBy(array(
                                'operator' => $user,
                                'request' => $request
                            ));

                            if (!is_null($or)) {
                                $or->setWorking(false);
                                $this->entityManager->persist($or);
                                $this->entityManager->flush($or);
                            }

                            $data = array(
                                'type' => 'operator_in_request',
                                'data' => $this->getOperatorData($request)
                            );

                            $this->subscribedTopics[$connTopics['request']->getId()]->broadcast($data);
                        }
                    }

                    if ($this->connectionsByOperatorInRequest[$connTopics['user']->getId()][$connTopics['request']->getId()] > 0) {
                        $this->connectionsByOperatorInRequest[$connTopics['user']->getId()][$connTopics['request']->getId()] --;
                    }
                }
                unset($this->connections[$conn->resourceId]);
                $conn->close();
            }

            public function onCall(ConnectionInterface $conn, $id, $topic, array $params)
            {
                // In this application if clients send data it's because the user hacked around in console
                $conn->callError($id, $topic, 'You are not allowed to make calls')->close();
            }

            public function onPublish(ConnectionInterface $conn, $topic, $event, array $exclude, array $eligible)
            {
                // In this application if clients send data it's because the user hacked around in console
                $conn->close();
            }

            public function onError(ConnectionInterface $conn, \Exception $e)
            {
                echo $e->getCode();
            }

            public function onEntry($entry)
            {
                $entry = json_decode($entry, true);

                usleep(100000);

                $map = array(
                    'notification' => 'onNotificationEntry',
                    'operator_in_request' => 'onOperatorEntry'
                );

                if (array_key_exists($entry['type'], $map) && method_exists($this, $map[$entry['type']])) {
                    $function = $map[$entry['type']];
                    $this->$function($entry);
                }
            }

            /**
             * @param string JSON'ified string we'll receive from ZeroMQ
             */
            public function onNotificationEntry($entry)
            {
                // Utilizado para dar tiempo a que la notificación se persista y la búsqueda no resulte nula.

                $notification = $this->entityManager
                        ->getRepository('Celsius3NotificationBundle:Notification')
                        ->find($entry['data']['notification_id']);

                if (!$notification) {
                    return;
                }

                foreach ($notification->getReceivers() as $user) {
                    // If the lookup topic object isn't set there is no one to publish to
                    if (!array_key_exists($user->getId(), $this->subscribedTopics)) {
                        return;
                    }

                    echo "Notifying to " . $user . "\n";

                    $notificationData = $this->getNotificationData(1, array($notification));

                    $data = array(
                        'type' => 'notification',
                        'data' => $notificationData
                    );

                    $topic = $this->subscribedTopics[$user->getId()];

                    $topic->broadcast($data);
                }
            }

            public function onOperatorEntry($entry)
            {
                
            }

        }
        