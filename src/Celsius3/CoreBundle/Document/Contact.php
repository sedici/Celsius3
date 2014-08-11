<?php

namespace Celsius3\CoreBundle\Document;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Gedmo\Timestampable\Traits\TimestampableDocument;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ODM\Document
 */
class Contact
{
    use TimestampableDocument;
    /**
     * @ODM\Id
     */
    private $id;
    /**
     * @Assert\NotBlank()
     * @ODM\String
     */
    private $name;
    /**
     * @Assert\NotBlank()
     * @ODM\String
     */
    private $surname;
    /**
     * @Assert\NotBlank()
     * @Assert\Email()
     * @ODM\String
     */
    private $email;
    /**
     * @Assert\NotBlank()
     * @ODM\String
     */
    private $address;
    /**
     * @ODM\ReferenceOne(targetDocument="BaseUser")
     */
    private $user;
    /**
     * @ODM\ReferenceOne(targetDocument="ContactType")
     */
    private $type;
    /**
     * @ODM\ReferenceOne(targetDocument="Instance")
     */
    private $instance;
    /**
     * @ODM\ReferenceOne(targetDocument="Institution", inversedBy="contacts")
     */
    private $institution;
    /**
     * @ODM\ReferenceOne(targetDocument="Instance")
     */
    private $owningInstance;

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
     * Set surname
     *
     * @param  string $surname
     * @return self
     */
    public function setSurname($surname)
    {
        $this->surname = $surname;

        return $this;
    }

    /**
     * Get surname
     *
     * @return string $surname
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * Set email
     *
     * @param  string $email
     * @return self
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string $email
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set address
     *
     * @param  string $address
     * @return self
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string $address
     */
    public function getAddress()
    {
        return $this->address;
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

    /**
     * Set type
     *
     * @param  Celsius3\CoreBundle\Document\ContactType $type
     * @return self
     */
    public function setType(\Celsius3\CoreBundle\Document\ContactType $type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return Celsius3\CoreBundle\Document\ContactType $type
     */
    public function getType()
    {
        return $this->type;
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
     * Set institution
     *
     * @param  Celsius3\CoreBundle\Document\Institution $institution
     * @return self
     */
    public function setInstitution(\Celsius3\CoreBundle\Document\Institution $institution)
    {
        $this->institution = $institution;

        return $this;
    }

    /**
     * Get institution
     *
     * @return Celsius3\CoreBundle\Document\Institution $institution
     */
    public function getInstitution()
    {
        return $this->institution;
    }

    /**
     * Set owningInstance
     *
     * @param  Celsius3\CoreBundle\Document\Instance $owningInstance
     * @return self
     */
    public function setOwningInstance(\Celsius3\CoreBundle\Document\Instance $owningInstance)
    {
        $this->owningInstance = $owningInstance;

        return $this;
    }

    /**
     * Get owningInstance
     *
     * @return Celsius3\CoreBundle\Document\Instance $owningInstance
     */
    public function getOwningInstance()
    {
        return $this->owningInstance;
    }
}