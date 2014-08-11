<?php

namespace Celsius3\CoreBundle\Document;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Gedmo\Timestampable\Traits\TimestampableDocument;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ODM\Document
 */
class CustomUserValue
{
    use TimestampableDocument;
    /**
     * @ODM\Id
     */
    private $id;
    /**
     * @ODM\String
     */
    private $value;
    /**
     * @Assert\NotNull
     * @ODM\ReferenceOne(targetDocument="CustomUserField", inversedBy="values")
     */
    private $field;
    /**
     * @Assert\NotNull
     * @ODM\ReferenceOne(targetDocument="BaseUser", inversedBy="customValues")
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
     * @param  string $value
     * @return self
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
     * @param  Celsius3\CoreBundle\Document\CustomUserField $field
     * @return self
     */
    public function setField(\Celsius3\CoreBundle\Document\CustomUserField $field)
    {
        $this->field = $field;

        return $this;
    }

    /**
     * Get field
     *
     * @return Celsius3\CoreBundle\Document\CustomUserField $field
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * Set user
     *
     * @param  Celsius3\CoreBundle\Document\BaseUser $user
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