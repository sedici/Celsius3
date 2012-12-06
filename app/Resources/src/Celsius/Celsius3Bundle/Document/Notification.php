<?php

namespace Celsius\Celsius3Bundle\Document;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document
 */
class Notification
{
    /**
     * @MongoDB\Id
     */
    private $id;

    /**
     * @Assert\NotBlank()
     * @MongoDB\String
     */
    private $type;
    
    /**
     * @Assert\NotBlank()
     * @MongoDB\String
     */
    private $text;
    
    /**
     * @Assert\Date()
     * @MongoDB\Date
     */
    private $created;
    
    /**
     * @Assert\NotBlank()
     * @Assert\Type(type="boolean")
     * @MongoDB\Boolean
     */
    private $viewed;
    
    /**
     * @MongoDB\ReferenceOne(targetDocument="Order") 
     */
    private $order;
    
    /**
     * @MongoDB\ReferenceOne(targetDocument="Instance") 
     */
    private $source;
    
    /**
     * @MongoDB\ReferenceOne(targetDocument="Instance") 
     */
    private $target;

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
     * Set type
     *
     * @param string $type
     * @return Notification
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * Get type
     *
     * @return string $type
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set text
     *
     * @param string $text
     * @return Notification
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
     * Set created
     *
     * @param date $created
     * @return Notification
     */
    public function setCreated($created)
    {
        $this->created = $created;
        return $this;
    }

    /**
     * Get created
     *
     * @return date $created
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set viewed
     *
     * @param boolean $viewed
     * @return Notification
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
     * Set order
     *
     * @param Celsius\Celsius3Bundle\Document\Order $order
     * @return Notification
     */
    public function setOrder(\Celsius\Celsius3Bundle\Document\Order $order)
    {
        $this->order = $order;
        return $this;
    }

    /**
     * Get order
     *
     * @return Celsius\Celsius3Bundle\Document\Order $order
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Set source
     *
     * @param Celsius\Celsius3Bundle\Document\Instance $source
     * @return Notification
     */
    public function setSource(\Celsius\Celsius3Bundle\Document\Instance $source)
    {
        $this->source = $source;
        return $this;
    }

    /**
     * Get source
     *
     * @return Celsius\Celsius3Bundle\Document\Instance $source
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * Set target
     *
     * @param Celsius\Celsius3Bundle\Document\Instance $target
     * @return Notification
     */
    public function setTarget(\Celsius\Celsius3Bundle\Document\Instance $target)
    {
        $this->target = $target;
        return $this;
    }

    /**
     * Get target
     *
     * @return Celsius\Celsius3Bundle\Document\Instance $target
     */
    public function getTarget()
    {
        return $this->target;
    }
}
