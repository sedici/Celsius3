<?php

namespace Celsius3\CoreBundle\Document;
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
        $this->created = new \DateTime();
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
     * @return self
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
     * @return self
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
     * @return self
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
     * Set source
     *
     * @param Celsius3\CoreBundle\Document\Instance $source
     * @return self
     */
    public function setSource(\Celsius3\CoreBundle\Document\Instance $source)
    {
        $this->source = $source;
        return $this;
    }

    /**
     * Get source
     *
     * @return Celsius3\CoreBundle\Document\Instance $source
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * Set template
     *
     * @param Celsius3\CoreBundle\Document\Template $template
     * @return self
     */
    public function setTemplate(\Celsius3\CoreBundle\Document\Template $template)
    {
        $this->template = $template;
        return $this;
    }

    /**
     * Get template
     *
     * @return Celsius3\CoreBundle\Document\Template $template
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * Set user
     *
     * @param Celsius3\CoreBundle\Document\BaseUser $user
     * @return self
     */
    public function setUser(\Celsius3\CoreBundle\Document\BaseUser $user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * Get user
     *
     * @return Celsius3\CoreBundle\Document\BaseUser $user
     */
    public function getUser()
    {
        return $this->user;
    }
}
