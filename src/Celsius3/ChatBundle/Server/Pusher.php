<?php

namespace Celsius3\ChatBundle\Server;

use Ratchet\ConnectionInterface;
use Ratchet\Wamp\WampServerInterface;
use Doctrine\ODM\MongoDB\DocumentManager;

class Pusher implements WampServerInterface
{

    /**
     * A lookup of all the topics clients have subscribed to
     */
    private $subscribedTopics = array();
    private $dm;

    public function __construct(DocumentManager $dm)
    {
        $this->dm = $dm;
    }

    public function onSubscribe(ConnectionInterface $conn, $topic)
    {
        // When a visitor subscribes to a topic link the Topic object in a  lookup array
        if (!array_key_exists($topic->getId(), $this->subscribedTopics)) {
            $this->subscribedTopics[$topic->getId()] = $topic;
            echo "User " . $topic->getId() . " subscribed.\n";
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

}
