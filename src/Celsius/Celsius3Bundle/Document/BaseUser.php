<?php

namespace Celsius\Celsius3Bundle\Document;

use FOS\UserBundle\Document\User;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document
 * @MongoDB\InheritanceType("SINGLE_COLLECTION")
 * @MongoDB\DiscriminatorField(fieldName="type")
 * @MongoDB\DiscriminatorMap({"baseuser"="BaseUser", "librarian"="Librarian", "admin"="Admin", "superadmin"="Superadmin"})
 * @MongoDB\Indexes({
 *   @MongoDB\Index(keys={"name"="asc"}),
 *   @MongoDB\Index(keys={"surname"="asc"}),
 * })
 * @MongoDB\Document(repositoryClass="Celsius\Celsius3Bundle\Repository\BaseUserRepository")
 */
class BaseUser extends User
{

    /**
     * @MongoDB\Id
     */
    protected $id;

    /**
     * @Assert\NotBlank()
     * @MongoDB\String
     */
    protected $name;

    /**
     * @Assert\NotBlank()
     * @MongoDB\String
     */
    protected $surname;

    /**
     * @Assert\Date()
     * @MongoDB\Date
     */
    protected $birthdate;

    /**
     * @Assert\NotBlank()
     * @MongoDB\String
     */
    protected $address;

    /**
     * @MongoDB\ReferenceMany(targetDocument="Order", mappedBy="owner")
     */
    protected $orders;

    /**
     * @MongoDB\ReferenceMany(targetDocument="Order", mappedBy="operator")
     */
    protected $operatedOrders;

    /**
     * @MongoDB\ReferenceMany(targetDocument="Order", mappedBy="creator")
     */
    protected $createdOrders;

    /**
     * @MongoDB\ReferenceOne(targetDocument="Instance") 
     */
    protected $instance;

    /**
     * @MongoDB\ReferenceOne(targetDocument="Librarian") 
     */
    protected $librarian;

    /**
     * @MongoDB\ReferenceOne(targetDocument="Institution") 
     */
    protected $institution;

    public function __toString()
    {
        return $this->getSurname() . ', ' . $this->getName();
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
     * Set name
     *
     * @param string $name
     * @return BaseUser
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
     * @return BaseUser
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
     * Set birthdate
     *
     * @param date $birthdate
     * @return BaseUser
     */
    public function setBirthdate($birthdate)
    {
        $this->birthdate = $birthdate;
        return $this;
    }

    /**
     * Get birthdate
     *
     * @return date $birthdate
     */
    public function getBirthdate()
    {
        return $this->birthdate;
    }

    /**
     * Set address
     *
     * @param string $address
     * @return BaseUser
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
     * Add orders
     *
     * @param Celsius\Celsius3Bundle\Document\Order $orders
     */
    public function addOrders($orders)
    {
        $this->orders[] = $orders;
    }

    /**
     * Get orders
     *
     * @return Doctrine\Common\Collections\Collection $orders
     */
    public function getOrders()
    {
        return $this->orders;
    }

    /**
     * Set instance
     *
     * @param Celsius\Celsius3Bundle\Document\Instance $instance
     * @return BaseUser
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
     * Set librarian
     *
     * @param Celsius\Celsius3Bundle\Document\Librarian $librarian
     * @return BaseUser
     */
    public function setLibrarian($librarian)
    {
        $this->librarian = $librarian;
        return $this;
    }

    /**
     * Get librarian
     *
     * @return Celsius\Celsius3Bundle\Document\Librarian $librarian
     */
    public function getLibrarian()
    {
        return $this->librarian;
    }

    /**
     * Add operatedOrders
     *
     * @param Celsius\Celsius3Bundle\Document\Order $operatedOrders
     */
    public function addOperatedOrders($operatedOrders)
    {
        $this->operatedOrders[] = $operatedOrders;
    }

    /**
     * Get operatedOrders
     *
     * @return Doctrine\Common\Collections\Collection $operatedOrders
     */
    public function getOperatedOrders()
    {
        return $this->operatedOrders;
    }

    /**
     * Add createdOrders
     *
     * @param Celsius\Celsius3Bundle\Document\Order $createdOrders
     */
    public function addCreatedOrders($createdOrders)
    {
        $this->createdOrders[] = $createdOrders;
    }

    /**
     * Get createdOrders
     *
     * @return Doctrine\Common\Collections\Collection $createdOrders
     */
    public function getCreatedOrders()
    {
        return $this->createdOrders;
    }

    /**
     * Set institution
     *
     * @param Celsius\Celsius3Bundle\Document\Institution $institution
     * @return BaseUser
     */
    public function setInstitution($institution)
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

    public function __construct()
    {
        parent::__construct();
        $this->orders = new \Doctrine\Common\Collections\ArrayCollection();
        $this->operatedOrders = new \Doctrine\Common\Collections\ArrayCollection();
        $this->createdOrders = new \Doctrine\Common\Collections\ArrayCollection();
    }

}
