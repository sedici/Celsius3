<?php

namespace Celsius3\CoreBundle\Document;
use FOS\UserBundle\Model\User;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use FOS\MessageBundle\Model\ParticipantInterface;
use Celsius3\CoreBundle\Manager\UserManager;
use Celsius3\NotificationBundle\Document\Notifiable;
use Celsius3\NotificationBundle\Manager\NotificationManager;

/**
 * @MongoDB\Document
 * @MongoDB\Indexes({
 *   @MongoDB\Index(keys={"name"="asc"}),
 *   @MongoDB\Index(keys={"surname"="asc"}),
 * })
 * @MongoDB\Document(repositoryClass="Celsius3\CoreBundle\Repository\BaseUserRepository")
 */
class BaseUser extends User implements ParticipantInterface, Notifiable
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

    public function notify(NotificationManager $manager)
    {
        $manager->notifyNewUser($this);
    }

    /**
     * @MongoDB\PrePersist
     */
    public function prePersist()
    {
        $this->addRole(UserManager::ROLE_USER);
    }

    public function __construct()
    {
        parent::__construct();
        $this->orders = new \Doctrine\Common\Collections\ArrayCollection();
        $this->operatedOrders = new \Doctrine\Common\Collections\ArrayCollection();
        $this->createdOrders = new \Doctrine\Common\Collections\ArrayCollection();
        $this->subordinates = new \Doctrine\Common\Collections\ArrayCollection();
        $this->customValues = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @param string $surname
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
     * Set birthdate
     *
     * @param date $birthdate
     * @return self
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
     * Add orders
     *
     * @param Celsius3\CoreBundle\Document\Order $orders
     */
    public function addOrder(\Celsius3\CoreBundle\Document\Order $orders)
    {
        $this->orders[] = $orders;
    }

    /**
     * Remove orders
     *
     * @param Celsius3\CoreBundle\Document\Order $orders
     */
    public function removeOrder(\Celsius3\CoreBundle\Document\Order $orders)
    {
        $this->orders->removeElement($orders);
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
     * @param Celsius3\CoreBundle\Document\Order $operatedOrders
     */
    public function addOperatedOrder(
            \Celsius3\CoreBundle\Document\Order $operatedOrders)
    {
        $this->operatedOrders[] = $operatedOrders;
    }

    /**
     * Remove operatedOrders
     *
     * @param Celsius3\CoreBundle\Document\Order $operatedOrders
     */
    public function removeOperatedOrder(
            \Celsius3\CoreBundle\Document\Order $operatedOrders)
    {
        $this->operatedOrders->removeElement($operatedOrders);
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
     * @param Celsius3\CoreBundle\Document\Order $createdOrders
     */
    public function addCreatedOrder(
            \Celsius3\CoreBundle\Document\Order $createdOrders)
    {
        $this->createdOrders[] = $createdOrders;
    }

    /**
     * Remove createdOrders
     *
     * @param Celsius3\CoreBundle\Document\Order $createdOrders
     */
    public function removeCreatedOrder(
            \Celsius3\CoreBundle\Document\Order $createdOrders)
    {
        $this->createdOrders->removeElement($createdOrders);
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
     * @param Celsius3\CoreBundle\Document\Instance $instance
     * @return self
     */
    public function setInstance(
            \Celsius3\CoreBundle\Document\Instance $instance)
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
     * Set librarian
     *
     * @param Celsius3\CoreBundle\Document\BaseUser $librarian
     * @return self
     */
    public function setLibrarian(
            \Celsius3\CoreBundle\Document\BaseUser $librarian)
    {
        $this->librarian = $librarian;
        return $this;
    }

    /**
     * Get librarian
     *
     * @return Celsius3\CoreBundle\Document\BaseUser $librarian
     */
    public function getLibrarian()
    {
        return $this->librarian;
    }

    /**
     * Set institution
     *
     * @param Celsius3\CoreBundle\Document\Institution $institution
     * @return self
     */
    public function setInstitution(
            \Celsius3\CoreBundle\Document\Institution $institution)
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
     * Add subordinates
     *
     * @param Celsius3\CoreBundle\Document\BaseUser $subordinates
     */
    public function addSubordinate(
            \Celsius3\CoreBundle\Document\BaseUser $subordinates)
    {
        $this->subordinates[] = $subordinates;
    }

    /**
     * Remove subordinates
     *
     * @param Celsius3\CoreBundle\Document\BaseUser $subordinates
     */
    public function removeSubordinate(
            \Celsius3\CoreBundle\Document\BaseUser $subordinates)
    {
        $this->subordinates->removeElement($subordinates);
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
     * @param Celsius3\CoreBundle\Document\CustomUserValue $customValues
     */
    public function addCustomValue(
            \Celsius3\CoreBundle\Document\CustomUserValue $customValues)
    {
        $this->customValues[] = $customValues;
    }

    /**
     * Remove customValues
     *
     * @param Celsius3\CoreBundle\Document\CustomUserValue $customValues
     */
    public function removeCustomValue(
            \Celsius3\CoreBundle\Document\CustomUserValue $customValues)
    {
        $this->customValues->removeElement($customValues);
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
