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
    private $notification_manager;
    private $em;

    public function __construct(NotificationManager $notification_manager, EntityManager $em)
    {
        $this->notification_manager = $notification_manager;
        $this->em = $em;
    }

    private function getNotificationData($count, $notifications)
    {
        $data = array(
            'count' => $count,
            'notifications' => array(),
        );

        foreach ($notifications as $notification) {
            $data['notifications'][] = array(
                'template' => $this->notification_manager->getRenderedTemplate($notification),
                'id' => $notification->getId(),
            );
        }

        return $data;
    }

    public function onSubscribe(ConnectionInterface $conn, $topic)
    {
        // When a visitor subscribes to a topic link the Topic object in a  lookup array
        if (!array_key_exists($topic->getId(), $this->subscribedTopics)) {
            $this->subscribedTopics[$topic->getId()] = $topic;
            echo "User " . $topic->getId() . " subscribed.\n";
        }

        $data = $this->getNotificationData($this->notification_manager->getUnreadNotificationsCount($topic->getId()), array_reverse($this->notification_manager->getUnreadNotifications($topic->getId())->toArray()));

        $topic->broadcast($data);
    }

    public function onUnSubscribe(ConnectionInterface $conn, $topic)
    {
        
    }

    public function onOpen(ConnectionInterface $conn)
    {
        
    }

    public function onClose(ConnectionInterface $conn)
    {
        
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
        
    }

    /**
     * @param string JSON'ified string we'll receive from ZeroMQ
     */
    public function onNotificationEntry($entry)
    {
        $entryData = json_decode($entry, true);

        $notification = $this->em
                ->getRepository('Celsius3NotificationBundle:Notification')
                ->find($entryData['notification_id']);

        if (!$notification) {
            return;
        }

        foreach ($notification->getReceivers() as $user) {
            // If the lookup topic object isn't set there is no one to publish to
            if (!array_key_exists($user->getId(), $this->subscribedTopics)) {
                return;
            }

            echo "Notifying to " . $user . "\n";

            $data = $this->getNotificationData(1, array($notification));

            $topic = $this->subscribedTopics[$user->getId()];

            $topic->broadcast($data);
        }
    }
}
