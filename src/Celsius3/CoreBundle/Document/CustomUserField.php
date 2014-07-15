<?php

namespace Celsius3\CoreBundle\Document;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @MongoDB\Document
 * @MongoDB\UniqueIndex(keys={"key"="asc", "instance"="asc"})
 */
class CustomUserField
{
    /**
     * @MongoDB\Id
     */
    private $id;

    /**
     * @Gedmo\Slug(fields={"name"})
     * @MongoDB\String
     */
    private $key;

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

    public function __construct()
    {
        $this->values = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set key
     *
     * @param  string $key
     * @return self
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
     * @param  string $name
     * @return self
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
     * @param  boolean $private
     * @return self
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
     * @param  boolean $required
     * @return self
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
     * @param  Celsius3\CoreBundle\Document\Instance $instance
     * @return self
     */
    public function setInstance(\Celsius3\CoreBundle\Document\Instance $instance)
    {
        $this->instance = $instance;

        return $this;
    }

    /**
     * Get instance
     *
     * @return Celsius3\CoreBundle\Document\Instance $instance
     */
    public function getInstance()
    {
        return $this->instance;
    }

    /**
     * Add values
     *
     * @param Celsius3\CoreBundle\Document\CustomUserValue $values
     */
    public function addValue(\Celsius3\CoreBundle\Document\CustomUserValue $values)
    {
        $this->values[] = $values;
    }

    /**
     * Remove values
     *
     * @param Celsius3\CoreBundle\Document\CustomUserValue $values
     */
    public function removeValue(\Celsius3\CoreBundle\Document\CustomUserValue $values)
    {
        $this->values->removeElement($values);
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