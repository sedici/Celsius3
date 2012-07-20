<?php

namespace Celsius\Celsius3Bundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document
 */
class Librarian extends BaseUser
{

    /**
     * @MongoDB\ReferenceMany(targetDocument="BaseUser", mappedBy="librarian")
     */
    protected $subordinates;

    /**
     * @var $id
     */
    protected $id;

    /**
     * @var string $name
     */
    protected $name;

    /**
     * @var string $surname
     */
    protected $surname;

    /**
     * @var date $birthdate
     */
    protected $birthdate;

    /**
     * @var string $address
     */
    protected $address;

    /**
     * @var Celsius\Celsius3Bundle\Document\Order
     */
    protected $orders = array();

    /**
     * @var Celsius\Celsius3Bundle\Document\Order
     */
    protected $operatedOrders = array();

    /**
     * @var Celsius\Celsius3Bundle\Document\Order
     */
    protected $createdOrders = array();

    /**
     * @var Celsius\Celsius3Bundle\Document\Instance
     */
    protected $instance;

    /**
     * @var Celsius\Celsius3Bundle\Document\Librarian
     */
    protected $librarian;

    /**
     * @var Celsius\Celsius3Bundle\Document\Institution
     */
    protected $institution;

    /**
     * @var Celsius\Celsius3Bundle\Document\Contact
     */
    protected $contact;

    public function __construct()
    {
        parent::__construct();
        $this->subordinates = new \Doctrine\Common\Collections\ArrayCollection();
        $this->orders = new \Doctrine\Common\Collections\ArrayCollection();
        $this->operatedOrders = new \Doctrine\Common\Collections\ArrayCollection();
        $this->createdOrders = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add subordinates
     *
     * @param Celsius\Celsius3Bundle\Document\BaseUser $subordinates
     */
    public function addSubordinates(\Celsius\Celsius3Bundle\Document\BaseUser $subordinates)
    {
        $this->subordinates[] = $subordinates;
    }

    /**
     * Get subordinates
     *
     * @return Doctrine\Common\Collections\Collection $subordinates
     */
    public function getSubordinates()
    {
        return $this->subordinates;
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
     * @return Librarian
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
     * @return Librarian
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
     * @return Librarian
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
     * @return Librarian
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
     * Set instance
     *
     * @param Celsius\Celsius3Bundle\Document\Instance $instance
     * @return Librarian
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
     * @return Librarian
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
     * Set institution
     *
     * @param Celsius\Celsius3Bundle\Document\Institution $institution
     * @return Librarian
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

    /**
     * Set contact
     *
     * @param Celsius\Celsius3Bundle\Document\Contact $contact
     * @return Librarian
     */
    public function setContact($contact)
    {
        $this->contact = $contact;
        return $this;
    }

    /**
     * Get contact
     *
     * @return Celsius\Celsius3Bundle\Document\Contact $contact
     */
    public function getContact()
    {
        return $this->contact;
    }

}
