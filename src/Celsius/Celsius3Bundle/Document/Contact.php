<?php

namespace Celsius\Celsius3Bundle\Document;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document
 */
class Contact
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
     * @Assert\NotBlank()
     * @MongoDB\String
     */
    private $surname;

    /**
     * @Assert\NotBlank()
     * @Assert\Email()
     * @MongoDB\String
     */
    private $email;

    /**
     * @Assert\NotBlank()
     * @MongoDB\String
     */
    private $address;

    /**
     * @MongoDB\ReferenceOne(targetDocument="BaseUser")
     */
    private $user;

    /**
     * @MongoDB\ReferenceOne(targetDocument="ContactType")
     */
    private $type;

    /**
     * @MongoDB\ReferenceOne(targetDocument="Instance")
     */
    private $instance;

    /**
     * @MongoDB\ReferenceOne(targetDocument="Institution", inversedBy="contacts")
     */
    private $institution;

    /**
     * @MongoDB\ReferenceOne(targetDocument="Instance")
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
     * @param string $name
     * @return Contact
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
     * @param string $surname
     * @return Contact
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
     * @param string $email
     * @return Contact
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
     * @param string $address
     * @return Contact
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
     * @param Celsius\Celsius3Bundle\Document\BaseUser $user
     * @return Contact
     */
    public function setUser($user)
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

    /**
     * Set instance
     *
     * @param Celsius\Celsius3Bundle\Document\Instance $instance
     * @return Contact
     */
    public function setInstance($instance)
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
     * Set type
     *
     * @param Celsius\Celsius3Bundle\Document\ContactType $type
     * @return Contact
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * Get type
     *
     * @return Celsius\Celsius3Bundle\Document\ContactType $type
     */
    public function getType()
    {
        return $this->type;
    }


    /**
     * Set institution
     *
     * @param Celsius\Celsius3Bundle\Document\Institution $institution
     * @return self
     */
    public function setInstitution(\Celsius\Celsius3Bundle\Document\Institution $institution)
    {
        $this->institution = $institution;
        return $this;
    }

    /**
     * Get institution
     *
     * @return Celsius\Celsius3Bundle\Document\Institution $institution
     */
    public function getInstitution()
    {
        return $this->institution;
    }

    /**
     * Set owningInstance
     *
     * @param Celsius\Celsius3Bundle\Document\Instance $owningInstance
     * @return self
     */
    public function setOwningInstance(\Celsius\Celsius3Bundle\Document\Instance $owningInstance)
    {
        $this->owningInstance = $owningInstance;
        return $this;
    }

    /**
     * Get owningInstance
     *
     * @return Celsius\Celsius3Bundle\Document\Instance $owningInstance
     */
    public function getOwningInstance()
    {
        return $this->owningInstance;
    }
}
