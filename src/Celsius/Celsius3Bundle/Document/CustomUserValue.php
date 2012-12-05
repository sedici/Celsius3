<?php

namespace Celsius\Celsius3Bundle\Document;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document
 */
class CustomUserValue
{

    /**
     * @MongoDB\Id
     */
    private $id;

    /**
     * @MongoDB\String
     */
    private $value;

    /**
     * @MongoDB\ReferenceOne(targetDocument="CustomUserField", inversedBy="values") 
     */
    private $field;

    /**
     * @MongoDB\ReferenceOne(targetDocument="BaseUser", inversedBy="customValues") 
     */
    private $user;

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
     * Set value
     *
     * @param string $value
     * @return CustomUserValue
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
     * Set field
     *
     * @param Celsius\Celsius3Bundle\Document\CustomUserField $field
     * @return CustomUserValue
     */
    public function setField(\Celsius\Celsius3Bundle\Document\CustomUserField $field)
    {
        $this->field = $field;
        return $this;
    }

    /**
     * Get field
     *
     * @return Celsius\Celsius3Bundle\Document\CustomUserField $field
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * Set user
     *
     * @param Celsius\Celsius3Bundle\Document\BaseUser $user
     * @return CustomUserValue
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
