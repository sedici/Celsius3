<?php

namespace Celsius3\ChatBundle\Server;

use Ratchet\ConnectionInterface;
use Ratchet\Wamp\WampServerInterface;
use Doctrine\ODM\MongoDB\DocumentManager;

class Pusher implements WampServerInterface
{

    private $rooms = array();
    private $roomLookup = array();
    private $dm;

    public function __construct(DocumentManager $dm)
    {
        $this->dm = $dm;
    }

    /**
     * {@inheritdoc}
     */
    public function onOpen(ConnectionInterface $conn)
    {
        $conn->Chat = new \StdClass;
        $conn->Chat->rooms = array();
        $conn->Chat->name = $conn->WAMP->sessionId;
        
        if (isset($conn->WebSocket)) {    
            $conn->Chat->name = $this->escape($conn->WebSocket->request->getCookie('name'));

            if (empty($conn->Chat->name)) {
                $conn->Chat->name = 'Anonymous ' . $conn->resourceId;
            }
        } else {
            $conn->Chat->name = 'Anonymous ' . $conn->resourceId;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function onClose(ConnectionInterface $conn)
    {
        foreach ($conn->Chat->rooms as $topic => $one) {
            $this->onUnSubscribe($conn, $topic);
        }
    }

    /**
     * {@inheritdoc}
     */
    function onCall(ConnectionInterface $conn, $id, $fn, array $params)
    {
        switch ($fn) {
            case 'setName':
                var_dump($params);
                break;
            default:
                return $conn->callError($id, 'Unknown call');
                break;
        }
    }

    /**
     * {@inheritdoc}
     */
    function onSubscribe(ConnectionInterface $conn, $topic)
    {
        var_dump($conn->WebSocket->request);
        echo $conn->Chat->name . " subscribed\n";
        
        // Room does not exist
        if (!array_key_exists($topic->getId(), $this->rooms)) {
            $this->rooms[$topic->getId()] = new \SplObjectStorage();
        }
        
        // Notify everyone this guy has joined the room they're in
        $this->broadcast($topic->getId(), array('joinRoom', $conn->WAMP->sessionId, $conn->Chat->name), $conn);

        // List all the people already in the room to the person who just joined
        foreach ($this->rooms[$topic->getId()] as $patron) {
            $conn->event($topic->getId(), array('joinRoom', $patron->WAMP->sessionId, $patron->Chat->name));
        }

        $this->rooms[$topic->getId()]->attach($conn);

        $conn->Chat->rooms[$topic->getId()] = 1;

        // Send all the rooms to the person who just subscribed to the room list
        foreach ($this->rooms as $room => $patrons) {
            $conn->event($topic->getId(), array($room, array_search($room, $this->roomLookup), 1));
        }
    }

    /**
     * {@inheritdoc}
     */
    function onUnSubscribe(ConnectionInterface $conn, $topic)
    {
        unset($conn->Chat->rooms[$topic]);
        $this->rooms[$topic]->detach($conn);

        if ($this->rooms[$topic]->count() == 0) {
            unset($this->rooms[$topic], $this->roomLookup[array_search($topic, $this->roomLookup)]);
        } else {
            $this->broadcast($topic, array('leftRoom', $conn->WAMP->sessionId));
        }
    }

    /**
     * {@inheritdoc}
     */
    function onPublish(ConnectionInterface $conn, $topic, $event, array $exclude = array(), array $eligible = array())
    {
        $event = (string) $event;
        if (empty($event)) {
            return;
        }

        if (!array_key_exists($topic->getId(), $conn->Chat->rooms) || !array_key_exists($topic->getId(), $this->rooms)) {
            // error, can not publish to a room you're not subscribed to
            // not sure how to handle error - WAMP spec doesn't specify
            // for now, we're going to silently fail

            return;
        }

        $event = $this->escape($event);

        $this->broadcast($topic->getId(), array('message', $conn->WAMP->sessionId, $event, date('c')));
    }

    /**
     * {@inheritdoc}
     */
    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        $conn->close();
    }

    protected function broadcast($topic, $msg, ConnectionInterface $exclude = null)
    {
        foreach ($this->rooms[$topic] as $client) {
            if ($client !== $exclude) {
                $client->event($topic, $msg);
            }
        }
    }

    /**
     * @param string
     * @return string
     */
    protected function escape($string)
    {
        return htmlspecialchars($string);
    }

}