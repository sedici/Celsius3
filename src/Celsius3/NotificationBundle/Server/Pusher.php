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

    public function __construct(NotificationManager $notificationManager, EntityManager $entityManager)
    {
        $this->notificationManager = $notificationManager;
        $this->entityManager = $entityManager;
    }

    private function testAndReconnect()
    {
        if ($this->entityManager->getConnection()->ping() === false) {
            $this->entityManager->getConnection()->close();
            $this->entityManager->getConnection()->connect();
        }
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

    private function getOperatorsData($request_id)
    {
        $data = array();
        foreach ($this->connectionsByOperatorInRequest[$request_id] as $key => $value) {
            $user = $this->entityManager->getRepository('Celsius3CoreBundle:BaseUser')
                    ->find($key);
            $data[$request_id][] = array(
                'operator_id' => $user->getId(),
                'operator_username' => base64_encode($user->getUsername()),
            );
        }
        return $data;
    }

    public function onSubscribe(ConnectionInterface $conn, $topic)
    {
        $this->testAndReconnect();
        dump($topic->getId());
        try {
            $map = array(
                'user' => function($conn, $topic) {
                    $id = explode('_', $topic->getId())[2];
                    $this->connections[$conn->resourceId]['user'] = $topic;
                    $notificationData = $this->getNotificationData($this->notificationManager->getUnreadNotificationsCount($id), array_reverse($this->notificationManager->getUnreadNotifications($id)));

                    return array(
                        'type' => 'notification',
                        'data' => $notificationData
                    );
                },
                        'request' => function($conn, $topic) {
                    $id = explode('_', $topic->getId())[2];
                    $user_id = explode('_', $topic->getId())[3];
                    $this->connections[$conn->resourceId]['request'] = $topic;

                    if (!array_key_exists($id, $this->connectionsByOperatorInRequest)) {
                        $this->connectionsByOperatorInRequest[$id] = array();
                    }

                    if (!array_key_exists($user_id, $this->connectionsByOperatorInRequest[$id])) {
                        $this->connectionsByOperatorInRequest[$id][$user_id] = 0;
                    }

                    $this->connectionsByOperatorInRequest[$id][$user_id] ++;

                    return array(
                        'type' => 'operator_in_request',
                        'data' => $this->getOperatorsData($id),
                    );
                }
                    );

                    $topicArray = explode('_', $topic->getId());
                    $data = $map[$topicArray[1]]($conn, $topic);

                    $generalTopic = implode('_', array_slice($topicArray, 0, 3));
                    if (!array_key_exists($generalTopic, $this->subscribedTopics)) {
                        $this->subscribedTopics[$generalTopic] = array();
                        echo $generalTopic . "\n";
                    }

                    $this->subscribedTopics[$generalTopic][] = $topic;
                    foreach ($this->subscribedTopics[$generalTopic] as $t) {
                        $t->broadcast($data);
                        unset($t);
                    }
                    unset($generalTopic, $data, $topicArray);
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
                $this->testAndReconnect();

                if (!array_key_exists($conn->resourceId, $this->connections)) {
                    return;
                }

                if (!array_key_exists('request', $this->connections[$conn->resourceId])) {
                    return;
                }

                $topic = $this->connections[$conn->resourceId]['request'];
                $topicArray = explode('_', $this->connections[$conn->resourceId]['request']->getId());
                $id = $topicArray[2];
                $user_id = $topicArray[3];

                if (array_key_exists($id, $this->connectionsByOperatorInRequest) && array_key_exists($user_id, $this->connectionsByOperatorInRequest[$id])) {
                    if (intval($this->connectionsByOperatorInRequest[$id][$user_id]) <= 1) {
                        unset($this->connectionsByOperatorInRequest[$id][$user_id]);

                        $data = array(
                            'type' => 'operator_in_request',
                            'data' => $this->getOperatorsData($id)
                        );

                        $generalTopic = implode('_', array_slice($topicArray, 0, 3));
                        foreach ($this->subscribedTopics[$generalTopic] as $t) {
                            $t->broadcast($data);
                            unset($t);
                        }
                        unset($generalTopic, $data);
                    } else {
                        $this->connectionsByOperatorInRequest[$id][$user_id] --;
                    }
                }
                $conn->close();
                unset($this->connections[$conn->resourceId], $topic, $topicArray, $id, $user_id, $conn);
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
                $this->testAndReconnect();

                $entry = json_decode($entry, true);

                usleep(100000);

                $notification = $this->entityManager
                        ->getRepository('Celsius3NotificationBundle:Notification')
                        ->find($entry['data']['notification_id']);

                if (!$notification) {
                    return;
                }

                foreach ($notification->getReceivers() as $user) {
                    $topic_id = 'c3_user_' . $user->getId();
                    // If the lookup topic object isn't set there is no one to publish to
                    if (!array_key_exists($topic_id, $this->subscribedTopics)) {
                        return;
                    }

                    echo "Notifying to " . $user . "\n";

                    $notificationData = $this->getNotificationData($this->notificationManager->getUnreadNotificationsCount($user->getId()), array($notification));

                    $data = array(
                        'type' => 'notification',
                        'data' => $notificationData
                    );

                    $topics = $this->subscribedTopics[$topic_id];
                    foreach ($topics as $topic) {
                        $topic->broadcast($data);
                    }
                    unset($data, $topic_id, $topic, $notificationData, $user);
                }
                unset($notification, $entry);
            }

        }
        