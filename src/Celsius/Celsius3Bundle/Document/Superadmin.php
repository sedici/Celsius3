<?php

namespace Celsius\Celsius3Bundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document
 */
class Superadmin extends Admin
{
    /**
     * @MongoDB\PrePersist 
     */
    public function prePersist()
    {
        $this->addRole('ROLE_SUPER_ADMIN');
    }
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
     * @var Celsius\Celsius3Bundle\Document\Message
     */
    protected $createdMessages = array();

    /**
     * @var Celsius\Celsius3Bundle\Document\Message
     */
    protected $receivedMessages = array();

    public function __construct()
    {
        parent::__construct();
        $this->orders = new \Doctrine\Common\Collections\ArrayCollection();
        $this->operatedOrders = new \Doctrine\Common\Collections\ArrayCollection();
        $this->createdOrders = new \Doctrine\Common\Collections\ArrayCollection();
        $this->createdMessages = new \Doctrine\Common\Collections\ArrayCollection();
        $this->receivedMessages = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Superadmin
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
     * @return Superadmin
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
     * @return Superadmin
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
     * @return Superadmin
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
    public function addOrders(\Celsius\Celsius3Bundle\Document\Order $orders)
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
    public function addOperatedOrders(\Celsius\Celsius3Bundle\Document\Order $operatedOrders)
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
    public function addCreatedOrders(\Celsius\Celsius3Bundle\Document\Order $createdOrders)
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
     * @return Superadmin
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
     * Set librarian
     *
     * @param Celsius\Celsius3Bundle\Document\Librarian $librarian
     * @return Superadmin
     */
    public function setLibrarian(\Celsius\Celsius3Bundle\Document\Librarian $librarian)
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
     * @return Superadmin
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
     * Add createdMessages
     *
     * @param Celsius\Celsius3Bundle\Document\Message $createdMessages
     */
    public function addCreatedMessages(\Celsius\Celsius3Bundle\Document\Message $createdMessages)
    {
        $this->createdMessages[] = $createdMessages;
    }

    /**
     * Get createdMessages
     *
     * @return Doctrine\Common\Collections\Collection $createdMessages
     */
    public function getCreatedMessages()
    {
        return $this->createdMessages;
    }

    /**
     * Add receivedMessages
     *
     * @param Celsius\Celsius3Bundle\Document\Message $receivedMessages
     */
    public function addReceivedMessages(\Celsius\Celsius3Bundle\Document\Message $receivedMessages)
    {
        $this->receivedMessages[] = $receivedMessages;
    }

    /**
     * Get receivedMessages
     *
     * @return Doctrine\Common\Collections\Collection $receivedMessages
     */
    public function getReceivedMessages()
    {
        return $this->receivedMessages;
    }
}
