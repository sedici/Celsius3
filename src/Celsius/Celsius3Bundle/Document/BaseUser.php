<?php

namespace Celsius\Celsius3Bundle\Document;

use FOS\UserBundle\Document\User;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use FOS\MessageBundle\Model\ParticipantInterface;

/**
 * @MongoDB\Document
 * @MongoDB\Indexes({
 *   @MongoDB\Index(keys={"name"="asc"}),
 *   @MongoDB\Index(keys={"surname"="asc"}),
 * })
 * @MongoDB\Document(repositoryClass="Celsius\Celsius3Bundle\Repository\BaseUserRepository")
 */
class BaseUser extends User implements ParticipantInterface
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
     * @Assert\NotBlank()
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
     * @MongoDB\ReferenceOne(targetDocument="BaseUser") 
     */
    protected $librarian;

    /**
     * @Assert\NotBlank()
     * @MongoDB\ReferenceOne(targetDocument="Institution") 
     */
    protected $institution;

    /**
     * @MongoDB\ReferenceMany(targetDocument="Message", mappedBy="sender")
     */
    protected $createdMessages;

    /**
     * @MongoDB\ReferenceMany(targetDocument="Message", mappedBy="receiver")
     */
    protected $receivedMessages;

    /**
     * @MongoDB\ReferenceMany(targetDocument="BaseUser", mappedBy="librarian")
     */
    protected $subordinates;

    /**
     * @MongoDB\ReferenceMany(targetDocument="CustomUserValue", mappedBy="user")
     */
    protected $customValues;

    public function __toString()
    {
        return $this->getSurname() . ', ' . $this->getName();
    }
    
    public function getFullName()
    {
        return $this->getSurname() . ', ' . $this->getName();
    }

    /**
     * @MongoDB\PrePersist 
     */
    public function prePersist()
    {
        $this->addRole('ROLE_USER');
    }

    public function __construct()
    {
        parent::__construct();
        $this->orders = new \Doctrine\Common\Collections\ArrayCollection();
        $this->operatedOrders = new \Doctrine\Common\Collections\ArrayCollection();
        $this->createdOrders = new \Doctrine\Common\Collections\ArrayCollection();
        $this->createdMessages = new \Doctrine\Common\Collections\ArrayCollection();
        $this->receivedMessages = new \Doctrine\Common\Collections\ArrayCollection();
        $this->subordinates = new \Doctrine\Common\Collections\ArrayCollection();
        $this->customFields = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return BaseUser
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
     * @param Celsius\Celsius3Bundle\Document\BaseUser $librarian
     * @return BaseUser
     */
    public function setLibrarian(\Celsius\Celsius3Bundle\Document\BaseUser $librarian)
    {
        $this->librarian = $librarian;
        return $this;
    }

    /**
     * Get librarian
     *
     * @return Celsius\Celsius3Bundle\Document\BaseUser $librarian
     */
    public function getLibrarian()
    {
        return $this->librarian;
    }

    /**
     * Set institution
     *
     * @param Celsius\Celsius3Bundle\Document\Institution $institution
     * @return BaseUser
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
     * Add customValues
     *
     * @param Celsius\Celsius3Bundle\Document\CustomUserValue $customValues
     */
    public function addCustomValues(\Celsius\Celsius3Bundle\Document\CustomUserValue $customValues)
    {
        $this->customValues[] = $customValues;
    }

    /**
     * Get customValues
     *
     * @return Doctrine\Common\Collections\Collection $customValues
     */
    public function getCustomValues()
    {
        return $this->customValues;
    }

}
