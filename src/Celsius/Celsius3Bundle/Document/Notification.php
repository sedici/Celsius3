<?php

namespace Celsius\Celsius3Bundle\Document;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use DateTime;
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
     * @Assert\NotBlank()
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
     * @Assert\NotBlank()
     */
    private $objectEvent;
    
    /**
     * @MongoDB\ReferenceOne(targetDocument="Instance") 
     */
    private $source;

    /**
     * @MongoDB\ReferenceOne(targetDocument="Template") 
     */
    private $template;
    
     /**
     * @MongoDB\ReferenceOne(targetDocument="BaseUser")
     */
    private $user;
    
    public function __construct()
    {
        $this->created = new DateTime();
        $this->viewed = false;
    }
    
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
     * @param string $viewed
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
     * @return string $viewed
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
     * @param Celsius\Celsius3Bundle\Document\Template $template
     * @return Notification
     */
    public function setTemplate(\Celsius\Celsius3Bundle\Document\Template $template)
    {
        $this->template = $template;
        return $this;
    }

    /**
     * Get template
     *
     * @return Celsius\Celsius3Bundle\Document\Template $template
     */
    public function getTemplate()
    {
        return $this->template;
    }
    
    /**
     * Set users
     *
     * @return Notification
     */
    public function setUser(\Celsius\Celsius3Bundle\Document\BaseUser $user)
    {
        $this->user = $user;
        return $this;
    }
     
    /**
     * Get user
     *
     * @return Doctrine\Common\Collections\Collection $user
     */
    public function getUser()
    {
        return $this->user;
    }
    
}
