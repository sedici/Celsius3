<?php

namespace Celsius\Celsius3Bundle\Topic;

use JDare\ClankBundle\Topic\TopicInterface;
use Ratchet\ConnectionInterface as Conn;
use Ratchet\Wamp\Topic;

class CelsiusTopic implements TopicInterface
{

    /**
     * This will receive any Subscription requests for this topic.
     *
     * @param \Ratchet\ConnectionInterface $conn
     * @param $topic
     * @return void
     */
    public function onSubscribe(Conn $conn, $topic)
    {
        var_dump('controler');die;
        //this will broadcast the message to ALL subscribers of this topic.
        $topic->broadcast($conn->resourceId . " has joined " . $topic->getId());
    }

    /**
     * This will receive any UnSubscription requests for this topic.
     *
     * @param \Ratchet\ConnectionInterface $conn
     * @param $topic
     * @return void
     */
    public function onUnSubscribe(Conn $conn, $topic)
    {
        var_dump('controler');die;
        //this will broadcast the message to ALL subscribers of this topic.
        $topic->broadcast($conn->resourceId . " has left " . $topic->getId());
    }


     /**
     * This will receive any Publish requests for this topic.
     *
     * @param \Ratchet\ConnectionInterface $conn
     * @param $topic
     * @param $event
     * @param array $exclude
     * @param array $eligible
     * @return mixed|void
     */
    public function onPublish(Conn $conn, $topic, $event, array $exclude, array $eligible)
    {
        var_dump('controler');die;
        /*
        $topic->getId() will contain the FULL requested uri, so you can proceed based on that

        e.g.

        if ($topic->getId() == "acme/channel/shout")
            //shout something to all subs.
        */
       $topic->broadcast(array(
            "sender" => $conn->resourceId,
            "topic" => $topic->getId(),
            "event" => $event
        ));
    }
    
    /*Esta funcion se invoca desde la administracion de mail, precisamente el index! */
    public function publish()
    {
        //Se crea un Topic 'celsius', para notificar a los intregantes del mismo.
        $new_topic = new Topic('celsius');
        $new_topic->broadcast(" hello! ");
        
    }

}