<?php

namespace Celsius\Celsius3Bundle\Document;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document
 * @MongoDB\UniqueIndex(keys={"name"="asc", "instance"="asc"})
 */
class CustomUserField
{

    /**
     * @MongoDB\Id
     */
    private $id;

    /**
     * @Assert\NotBlank()
     * @MongoDB\String
     */
    private $name;

    /**
     * @Assert\Type(type="boolean")
     * @MongoDB\Boolean
     */
    private $private = true;

    /**
     * @Assert\Type(type="boolean")
     * @MongoDB\Boolean
     */
    private $required = true;

    /**
     * @MongoDB\ReferenceOne(targetDocument="Instance") 
     */
    private $instance;
    
    /**
     * @MongoDB\ReferenceMany(targetDocument="CustomUserValue", mappedBy="field") 
     */
    private $values;

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
     * Set name
     *
     * @param string $name
     * @return CustomUserField
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Get name
     *
     * @return string $name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set private
     *
     * @param boolean $private
     * @return CustomUserField
     */
    public function setPrivate($private)
    {
        $this->private = $private;
        return $this;
    }

    /**
     * Get private
     *
     * @return boolean $private
     */
    public function getPrivate()
    {
        return $this->private;
    }

    /**
     * Set required
     *
     * @param boolean $required
     * @return CustomUserField
     */
    public function setRequired($required)
    {
        $this->required = $required;
        return $this;
    }

    /**
     * Get required
     *
     * @return boolean $required
     */
    public function getRequired()
    {
        return $this->required;
    }

    /**
     * Set instance
     *
     * @param Celsius\Celsius3Bundle\Document\Instance $instance
     * @return CustomUserField
     */
    public function setInstance(\Celsius\Celsius3Bundle\Document\Instance $instance)
    {
        $this->instance = $instance;
        return $this;
    }

    /**
     * Get instance
     *
     * @return Celsius\Celsius3Bundle\Document\Instance $instance
     */
    public function getInstance()
    {
        return $this->instance;
    }

    public function __construct()
    {
        $this->values = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add values
     *
     * @param Celsius\Celsius3Bundle\Document\CustomUserValue $values
     */
    public function addValues(\Celsius\Celsius3Bundle\Document\CustomUserValue $values)
    {
        $this->values[] = $values;
    }

    /**
     * Get values
     *
     * @return Doctrine\Common\Collections\Collection $values
     */
    public function getValues()
    {
        return $this->values;
    }
}
