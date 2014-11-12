<?php

/*
 * Celsius3 - Order management
 * Copyright (C) 2014 PrEBi <info@prebi.unlp.edu.ar>
 *
 * This file is part of Celsius3.
 *
 * Celsius3 is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Celsius3 is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Celsius3.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Celsius3\CoreBundle\Entity;

use FOS\UserBundle\Model\User;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use FOS\MessageBundle\Model\ParticipantInterface;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Gedmo\Mapping\Annotation as Gedmo;
use Celsius3\CoreBundle\Manager\UserManager;
use Celsius3\NotificationBundle\Entity\Notifiable;
use Celsius3\NotificationBundle\Manager\NotificationManager;

/**
 * @ORM\Entity(repositoryClass="Celsius3\CoreBundle\Repository\BaseUserRepository")
 * @ORM\Table(name="user")
 * @ORM\HasLifecycleCallbacks
 */
class BaseUser extends User implements ParticipantInterface, Notifiable
{

    use TimestampableEntity;
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="string", length=255)
     */
    protected $name;
    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="string", length=255)
     */
    protected $surname;
    /**
     * @Assert\NotBlank()
     * @Assert\Date()
     * @ORM\Column(type="date", nullable=true)
     */
    protected $birthdate;
    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="string", length=255)
     */
    protected $address;
    /**
     * @Assert\NotNull()
     * @Assert\Type(type="boolean")
     * @ORM\Column(type="boolean")
     */
    protected $downloadAuth = true;
    /**
     * @Assert\NotNull()
     * @Assert\Type(type="boolean")
     * @ORM\Column(type="boolean")
     */
    protected $wrongEmail = false;
    /**
     * @ORM\OneToMany(targetEntity="Request", mappedBy="owner")
     */
    protected $orders;
    /**
     * @ORM\OneToMany(targetEntity="Request", mappedBy="operator")
     */
    protected $operatedOrders;
    /**
     * @ORM\OneToMany(targetEntity="Request", mappedBy="creator")
     */
    protected $createdOrders;
    /**
     * @Assert\NotNull
     * @ORM\ManyToOne(targetEntity="Instance", inversedBy="users")
     * @ORM\JoinColumn(name="instance_id", referencedColumnName="id", nullable=false)
     */
    protected $instance;
    /**
     * @Assert\NotNull
     * @ORM\ManyToOne(targetEntity="Institution", inversedBy="users")
     * @ORM\JoinColumn(name="institution_id", referencedColumnName="id", nullable=false)
     */
    protected $institution;
    /**
     * @ORM\OneToMany(targetEntity="CustomUserValue", mappedBy="user")
     */
    protected $customValues;
    /**
     * @ORM\ManyToMany(targetEntity="Instance")
     * @ORM\JoinTable(name="admin_instance",
     *      joinColumns={@ORM\JoinColumn(name="instance_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")}
     *      )
     */
    protected $administeredInstances;

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
     * @ORM\PrePersist
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
        $this->customValues = new \Doctrine\Common\Collections\ArrayCollection();
        $this->administeredInstances = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set birthdate
     *
     * @param  date $birthdate
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
     * Add orders
     *
     * @param Celsius3\CoreBundle\Entity\Order $orders
     */
    public function addOrder(\Celsius3\CoreBundle\Entity\Order $orders)
    {
        $this->orders[] = $orders;
    }

    /**
     * Remove orders
     *
     * @param Celsius3\CoreBundle\Entity\Order $orders
     */
    public function removeOrder(\Celsius3\CoreBundle\Entity\Order $orders)
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
     * @param Celsius3\CoreBundle\Entity\Order $operatedOrders
     */
    public function addOperatedOrder(
    \Celsius3\CoreBundle\Entity\Order $operatedOrders)
    {
        $this->operatedOrders[] = $operatedOrders;
    }

    /**
     * Remove operatedOrders
     *
     * @param Celsius3\CoreBundle\Entity\Order $operatedOrders
     */
    public function removeOperatedOrder(
    \Celsius3\CoreBundle\Entity\Order $operatedOrders)
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
     * @param Celsius3\CoreBundle\Entity\Order $createdOrders
     */
    public function addCreatedOrder(
    \Celsius3\CoreBundle\Entity\Order $createdOrders)
    {
        $this->createdOrders[] = $createdOrders;
    }

    /**
     * Remove createdOrders
     *
     * @param Celsius3\CoreBundle\Entity\Order $createdOrders
     */
    public function removeCreatedOrder(
    \Celsius3\CoreBundle\Entity\Order $createdOrders)
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
     * @param  Celsius3\CoreBundle\Entity\Instance $instance
     * @return self
     */
    public function setInstance(
    \Celsius3\CoreBundle\Entity\Instance $instance)
    {
        $this->instance = $instance;

        return $this;
    }

    /**
     * Get instance
     *
     * @return Celsius3\CoreBundle\Entity\Instance $instance
     */
    public function getInstance()
    {
        return $this->instance;
    }

    /**
     * Set librarian
     *
     * @param  Celsius3\CoreBundle\Entity\BaseUser $librarian
     * @return self
     */
    public function setLibrarian(
    \Celsius3\CoreBundle\Entity\BaseUser $librarian)
    {
        $this->librarian = $librarian;

        return $this;
    }

    /**
     * Get librarian
     *
     * @return Celsius3\CoreBundle\Entity\BaseUser $librarian
     */
    public function getLibrarian()
    {
        return $this->librarian;
    }

    /**
     * Set institution
     *
     * @param  Celsius3\CoreBundle\Entity\Institution $institution
     * @return self
     */
    public function setInstitution(
    \Celsius3\CoreBundle\Entity\Institution $institution)
    {
        $this->institution = $institution;

        return $this;
    }

    /**
     * Get institution
     *
     * @return Celsius3\CoreBundle\Entity\Institution $institution
     */
    public function getInstitution()
    {
        return $this->institution;
    }

    /**
     * Add customValues
     *
     * @param Celsius3\CoreBundle\Entity\CustomUserValue $customValues
     */
    public function addCustomValue(\Celsius3\CoreBundle\Entity\CustomUserValue $customValues)
    {
        $this->customValues[] = $customValues;
    }

    /**
     * Remove customValues
     *
     * @param Celsius3\CoreBundle\Entity\CustomUserValue $customValues
     */
    public function removeCustomValue(\Celsius3\CoreBundle\Entity\CustomUserValue $customValues)
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

    /**
     * Set downloadAuth
     *
     * @param  boolean $downloadAuth
     * @return self
     */
    public function setDownloadAuth($downloadAuth)
    {
        $this->downloadAuth = $downloadAuth;

        return $this;
    }

    /**
     * Get downloadAuth
     *
     * @return boolean $downloadAuth
     */
    public function getDownloadAuth()
    {
        return $this->downloadAuth;
    }

    /**
     * Add administeredInstance
     *
     * @param Celsius3\CoreBundle\Entity\Instance $administeredInstance
     */
    public function addAdministeredInstance(\Celsius3\CoreBundle\Entity\Instance $administeredInstance)
    {
        $this->administeredInstances[] = $administeredInstance;
    }

    /**
     * Remove administeredInstance
     *
     * @param Celsius3\CoreBundle\Entity\Instance $administeredInstance
     */
    public function removeAdministeredInstance(\Celsius3\CoreBundle\Entity\Instance $administeredInstance)
    {
        $this->administeredInstances->removeElement($administeredInstance);
    }

    /**
     * Get administeredInstances
     *
     * @return Doctrine\Common\Collections\Collection $administeredInstances
     */
    public function getAdministeredInstances()
    {
        return $this->administeredInstances;
    }

    public function getCountry()
    {
        return $this->getInstitution()->getCountry();
    }

    public function getCity()
    {
        return $this->getInstitution()->getCity();
    }

    public function getIsLibrarian()
    {
        return in_array(UserManager::ROLE_LIBRARIAN, $this->getRoles());
    }

    /**
     * Set wrongEmail
     *
     * @param boolean $wrongEmail
     * @return self
     */
    public function setWrongEmail($wrongEmail)
    {
        $this->wrongEmail = $wrongEmail;
        return $this;
    }

    /**
     * Get wrongEmail
     *
     * @return boolean $wrongEmail
     */
    public function getWrongEmail()
    {
        return $this->wrongEmail;
    }
}
