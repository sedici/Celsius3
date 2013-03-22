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
    private $cause;
    
    /**
     * @Assert\Date()
     * @MongoDB\Date
     */
    private $created;
    
    /**
     * @Assert\Date()
     * @MongoDB\Date
     */
    private $viewed;
    
    /**
     * @Assert\NotBlank()
     */
    private $objectEvent;
    
    /**
     * @MongoDB\ReferenceOne(targetDocument="Instance") 
     */
    private $source;

    /**
     * @MongoDB\ReferenceOne(targetDocument="NotificationTemplate") 
     */
    private $template;
    
     /**
     * @MongoDB\ReferenceMany(targetDocument="BaseUser")
     */
    private $users;
    
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
     * Set cause
     *
     * @param string $cause
     * @return Notification
     */
    public function setCause($cause)
    {
        $this->cause = $cause;
        return $this;
    }

    /**
     * Get cause
     *
     * @return string $cause
     */
    public function getCause()
    {
        return $this->cause;
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
     * @param date $viewed
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
     * @return date $viewed
     */
    public function getViewed()
    {
        return $this->viewed;
    }

    /**
     * Set objectEvent
     *
     * 
     * @return Notification
     */
    public function setObjectEvent($objectEvent)
    {
        $this->objectEvent = $objectEvent;
        return $this;
    }

    /**
     * Get objectEvent
     *
     * 
     */
    public function getObjectEvent()
    {
        return $this->objectEvent;
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
     * Set template
     *
     * @param Celsius\Celsius3Bundle\Document\NotificationTemplate $template
     * @return Notification
     */
    public function setTemplate(\Celsius\Celsius3Bundle\Document\NotificationTemplate $template)
    {
        $this->template = $template;
        return $this;
    }

    /**
     * Get template
     *
     * @return Celsius\Celsius3Bundle\Document\NotificationTemplate $template
     */
    public function getTemplate()
    {
        return $this->template;
    }
    
    /**
     * Add users
     *
     * @param Celsius\Celsius3Bundle\Document\BaseUser $users
     */
    public function addUsers(\Celsius\Celsius3Bundle\Document\BaseUser $users)
    {
        $this->users[] = $users;
    }

    /**
     * Get users
     *
     * @return Doctrine\Common\Collections\Collection $users
     */
    public function getUsers()
    {
        return $this->users;
    }
    
}
