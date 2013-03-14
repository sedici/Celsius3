<?php

namespace Celsius\Celsius3Bundle\Document;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document
 */
class Configuration
{

    /**
     * @MongoDB\Id
     */
    private $id;

    /**
     * @Assert\NotBlank()
     * @MongoDB\String
     */
    private $key;

    /**
     * @Assert\NotBlank()
     * @MongoDB\String
     */
    private $name;

    /**
     * @MongoDB\String
     */
    private $value;

    /**
     * @Assert\NotBlank()
     * @MongoDB\String
     */
    private $type;

    /**
     * @MongoDB\ReferenceOne(targetDocument="Instance") 
     */
    private $instance;

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
     * Set key
     *
     * @param string $key
     * @return Configuration
     */
    public function setKey($key)
    {
        $this->key = $key;
        return $this;
    }

    /**
     * Get key
     *
     * @return string $key
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * Set value
     *
     * @param string $value
     * @return Configuration
     */
    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }

    /**
     * Get value
     *
     * @return string $value
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set instance
     *
     * @param Celsius\Celsius3Bundle\Document\Instance $instance
     * @return Configuration
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

    /**
     * Set name
     *
     * @param string $name
     * @return Configuration
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
     * Set type
     *
     * @param string $type
     * @return Configuration
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

}
