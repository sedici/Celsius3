<?php
namespace Celsius3\NotificationBundle\Server;
use Ratchet\ConnectionInterface;
use Ratchet\Wamp\WampServerInterface;

class Pusher implements WampServerInterface
{
    /**
     * A lookup of all the topics clients have subscribed to
     */
    protected $subscribedTopics = array();

    public function onSubscribe(ConnectionInterface $conn, $topic)
    {
        // When a visitor subscribes to a topic link the Topic object in a  lookup array
        if (!array_key_exists($topic->getId(), $this->subscribedTopics)) {
            $this->subscribedTopics[$topic->getId()] = $topic;
        }
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

        foreach ($entryData['user_ids'] as $user_id) {
            // If the lookup topic object isn't set there is no one to publish to
            if (!array_key_exists($user_id, $this->subscribedTopics)) {
                return;
            }

            echo "Notifying to " . $user_id . "\n";

            $topic = $this->subscribedTopics[$user_id];

            $topic->broadcast($entryData);
        }
    }
}
