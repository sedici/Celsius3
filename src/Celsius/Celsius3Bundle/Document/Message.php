<?php

namespace Celsius\Celsius3Bundle\Document;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document
 */
class Message
{
    /**
     * @MongoDB\Id
     */
    private $id;

    /**
     * @MongoDB\String
     */
    private $subject;
    
    /**
     * @MongoDB\String
     */
    private $text;
    
    /**
     * @Assert\NotBlank()
     * @Assert\Date()
     * @MongoDB\Date
     */
    private $date;
    
    /**
     * @Assert\NotBlank()
     * @Assert\Type(type="boolean")
     * @MongoDB\Boolean
     */
    private $viewed;
    
    /**
     * @MongoDB\ReferenceOne(targetDocument="BaseUser") 
     */
    private $sender;
    
    /**
     * @MongoDB\ReferenceOne(targetDocument="BaseUser") 
     */
    private $receiver;

    /**
     * Get id
     *
     * @return id $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set subject
     *
     * @param string $subject
     * @return Message
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
        return $this;
    }

    /**
     * Get subject
     *
     * @return string $subject
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Set text
     *
     * @param string $text
     * @return Message
     */
    public function setText($text)
    {
        $this->text = $text;
        return $this;
    }

    /**
     * Get text
     *
     * @return string $text
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set date
     *
     * @param date $date
     * @return Message
     */
    public function setDate($date)
    {
        $this->date = $date;
        return $this;
    }

    /**
     * Get date
     *
     * @return date $date
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set viewed
     *
     * @param boolean $viewed
     * @return Message
     */
    public function setViewed($viewed)
    {
        $this->viewed = $viewed;
        return $this;
    }

    /**
     * Get viewed
     *
     * @return boolean $viewed
     */
    public function getViewed()
    {
        return $this->viewed;
    }

    /**
     * Set sender
     *
     * @param Celsius\Celsius3Bundle\Document\BaseUser $sender
     * @return Message
     */
    public function setSender(\Celsius\Celsius3Bundle\Document\BaseUser $sender)
    {
        $this->sender = $sender;
        return $this;
    }

    /**
     * Get sender
     *
     * @return Celsius\Celsius3Bundle\Document\BaseUser $sender
     */
    public function getSender()
    {
        return $this->sender;
    }

    /**
     * Set receiver
     *
     * @param Celsius\Celsius3Bundle\Document\BaseUser $receiver
     * @return Message
     */
    public function setReceiver(\Celsius\Celsius3Bundle\Document\BaseUser $receiver)
    {
        $this->receiver = $receiver;
        return $this;
    }

    /**
     * Get receiver
     *
     * @return Celsius\Celsius3Bundle\Document\BaseUser $receiver
     */
    public function getReceiver()
    {
        return $this->receiver;
    }
}
