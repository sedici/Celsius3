<?php

namespace Celsius\Celsius3Bundle\Document;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document
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
     * @Assert\NotBlank()
     * @Assert\Type(type="boolean")
     * @MongoDB\Boolean
     */
    private $private = true;

    /**
     * @MongoDB\ReferenceOne(targetDocument="BaseUser", inversedBy="customFields") 
     */
    private $user;

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
     * @return CustomUserField
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
     * Set value
     *
     * @param string $value
     * @return CustomUserField
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
     * Set type
     *
     * @param string $type
     * @return CustomUserField
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

    /**
     * Set user
     *
     * @param Celsius\Celsius3Bundle\Document\BaseUser $user
     * @return CustomUserField
     */
    public function setUser(\Celsius\Celsius3Bundle\Document\BaseUser $user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * Get user
     *
     * @return Celsius\Celsius3Bundle\Document\BaseUser $user
     */
    public function getUser()
    {
        return $this->user;
    }

}
