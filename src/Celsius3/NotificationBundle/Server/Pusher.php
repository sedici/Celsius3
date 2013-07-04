<?php
namespace Celsius3\NotificationBundle\Server;
use Ratchet\ConnectionInterface;
use Ratchet\Wamp\WampServerInterface;
use Celsius3\NotificationBundle\Manager\NotificationManager;
use Doctrine\ODM\MongoDB\DocumentManager;

class Pusher implements WampServerInterface
{
    /**
     * A lookup of all the topics clients have subscribed to
     */
    private $subscribedTopics = array();
    private $notification_manager;
    private $dm;

    public function __construct(NotificationManager $notification_manager,
            DocumentManager $dm)
    {
        $this->notification_manager = $notification_manager;
        $this->dm = $dm;
    }

    public function onSubscribe(ConnectionInterface $conn, $topic)
    {
        // When a visitor subscribes to a topic link the Topic object in a  lookup array
        if (!array_key_exists($topic->getId(), $this->subscribedTopics)) {
            $this->subscribedTopics[$topic->getId()] = $topic;
            echo "User " . $topic->getId() . " subscribed.\n";
        }

        $data = array(
                'count' => $this->notification_manager
                        ->getUnreadNotificationsCount($topic->getId()),
                'notifications' => array(),);

        $notifications = array_reverse(
                $this->notification_manager
                        ->getUnreadNotifications($topic->getId())->toArray());
        foreach ($notifications as $notification) {
            $data['notifications'][] = array(
                    'template' => $this->notification_manager
                            ->getRenderedTemplate($notification),
                    'id' => $notification->getId(),);
        }

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

    public function onCall(ConnectionInterface $conn, $id, $topic,
            array $params)
    {
        // In this application if clients send data it's because the user hacked around in console
        $conn->callError($id, $topic, 'You are not allowed to make calls')
                ->close();
    }

    public function onPublish(ConnectionInterface $conn, $topic, $event,
            array $exclude, array $eligible)
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

        $notification = $this->dm
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

            $notification_data = array(
                    'template' => $this->notification_manager
                            ->getRenderedTemplate($notification),
                    'id' => $notification->getId(),);

            $topic = $this->subscribedTopics[$user->getId()];

            $topic
                    ->broadcast(
                            array('count' => 1,
                                    'notifications' => $notification_data));
        }
    }
}
