<?php

/*
 * Celsius3 - Order management
 * Copyright (C) 2014 PREBI-SEDICI <info@prebi.unlp.edu.ar> http://prebi.unlp.edu.ar http://sedici.unlp.edu.ar
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
use Celsius3\CoreBundle\Manager\UserManager;
use Celsius3\NotificationBundle\Entity\Notifiable;
use Celsius3\NotificationBundle\Manager\NotificationManager;
use Celsius3\ApiBundle\Entity\Client;
use EWZ\Bundle\RecaptchaBundle\Validator\Constraints as Recaptcha;

/**
 * @ORM\Entity(repositoryClass="Celsius3\CoreBundle\Repository\BaseUserRepository")
 * @ORM\Table(name="user", indexes={
 *   @ORM\Index(name="idx_username", columns={"username"}),
 *   @ORM\Index(name="idx_email", columns={"email"}),
 *   @ORM\Index(name="idx_name", columns={"name"}),
 *   @ORM\Index(name="idx_surname", columns={"surname"}),
 *   @ORM\Index(name="idx_enabled", columns={"enabled"}),
 *   @ORM\Index(name="idx_locked", columns={"locked"}),
 *   @ORM\Index(name="idx_instance", columns={"instance_id"}),
 *   @ORM\Index(name="idx_institution", columns={"institution_id"})
 * })
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
     * @Assert\NotBlank(groups={"Default"})
     * @ORM\Column(type="string", length=255)
     */
    protected $name;

    /**
     * @Assert\NotBlank(groups={"Default"})
     * @ORM\Column(type="string", length=255)
     */
    protected $surname;

    /**
     * @Assert\Date(groups={"Default"})
     * @ORM\Column(type="date", nullable=true)
     */
    protected $birthdate;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
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
     * @Assert\NotNull()
     * @Assert\Type(type="boolean")
     * @ORM\Column(type="boolean")
     */
    protected $pdf = true;

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
     * @ORM\Column(type="array", name="secondary_instances")
     */
    protected $secondaryInstances = array();

    /**
     * @ORM\OneToMany(targetEntity="CustomUserValue", mappedBy="user", cascade={"remove"})
     */
    protected $customValues;

    /**
     * @ORM\ManyToMany(targetEntity="Celsius3\ApiBundle\Entity\Client")
     * @ORM\JoinTable(name="user_client",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="client_id", referencedColumnName="id")}
     *      )
     */
    protected $clientApplications;

    /**
     * @ORM\OneToMany(targetEntity="Celsius3\NotificationBundle\Entity\NotificationSettings", mappedBy="user", cascade={"remove"})
     */
    protected $notificationSettings;

    /**
     * @ORM\OneToMany(targetEntity="Celsius3\NotificationBundle\Entity\BaseUserNotification", mappedBy="object", cascade={"remove"})
     */
    protected $notifications;

    /**
     * @ORM\ManyToMany(targetEntity="Institution" , inversedBy="librarian")
     * @ORM\JoinTable(name="librarian_institution",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="institution_id", referencedColumnName="id")}
     *      )
     */
    protected $librarianInstitution;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $observaciones;

    /**
     * @Recaptcha\IsTrue
     */
    public $recaptcha;

    public function __toString()
    {
<<<<<<< a35da8ef2ad6afcf98c356fd19d91d74692171ad
        return ucwords(strtolower($this->getSurname())).', '.ucwords(strtolower($this->getName()));
=======
        return ucwords(strtolower($this->getSurname())) . ', ' . ucwords(strtolower($this->getName()));
>>>>>>> se agrego campo observaciones en usuario y se agrego a las instancias
    }

    public function getFullName()
    {
<<<<<<< a35da8ef2ad6afcf98c356fd19d91d74692171ad
        return $this->getSurname().', '.$this->getName();
=======
        return $this->getSurname() . ', ' . $this->getName();
>>>>>>> se agrego campo observaciones en usuario y se agrego a las instancias
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
        $this->clientApplications = new \Doctrine\Common\Collections\ArrayCollection();
        $this->notifications = new \Doctrine\Common\Collections\ArrayCollection();
        $this->librarianInstitution = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
<<<<<<< a35da8ef2ad6afcf98c356fd19d91d74692171ad
     * Get id.
=======
     * Get id
>>>>>>> se agrego campo observaciones en usuario y se agrego a las instancias
     *
     * @return id $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
<<<<<<< a35da8ef2ad6afcf98c356fd19d91d74692171ad
     * Set name.
     *
     * @param string $name
     *
=======
     * Set name
     *
     * @param  string $name
>>>>>>> se agrego campo observaciones en usuario y se agrego a las instancias
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
<<<<<<< a35da8ef2ad6afcf98c356fd19d91d74692171ad
     * Get name.
=======
     * Get name
>>>>>>> se agrego campo observaciones en usuario y se agrego a las instancias
     *
     * @return string $name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
<<<<<<< a35da8ef2ad6afcf98c356fd19d91d74692171ad
     * Set surname.
     *
     * @param string $surname
     *
=======
     * Set surname
     *
     * @param  string $surname
>>>>>>> se agrego campo observaciones en usuario y se agrego a las instancias
     * @return self
     */
    public function setSurname($surname)
    {
        $this->surname = $surname;

        return $this;
    }

    /**
<<<<<<< a35da8ef2ad6afcf98c356fd19d91d74692171ad
     * Get surname.
=======
     * Get surname
>>>>>>> se agrego campo observaciones en usuario y se agrego a las instancias
     *
     * @return string $surname
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
<<<<<<< a35da8ef2ad6afcf98c356fd19d91d74692171ad
     * Set birthdate.
     *
     * @param date $birthdate
     *
=======
     * Set birthdate
     *
     * @param  date $birthdate
>>>>>>> se agrego campo observaciones en usuario y se agrego a las instancias
     * @return self
     */
    public function setBirthdate($birthdate)
    {
        $this->birthdate = $birthdate;

        return $this;
    }

    /**
<<<<<<< a35da8ef2ad6afcf98c356fd19d91d74692171ad
     * Get birthdate.
=======
     * Get birthdate
>>>>>>> se agrego campo observaciones en usuario y se agrego a las instancias
     *
     * @return date $birthdate
     */
    public function getBirthdate()
    {
        return $this->birthdate;
    }

    /**
<<<<<<< a35da8ef2ad6afcf98c356fd19d91d74692171ad
     * Set address.
     *
     * @param string $address
     *
=======
     * Set address
     *
     * @param  string $address
>>>>>>> se agrego campo observaciones en usuario y se agrego a las instancias
     * @return self
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
<<<<<<< a35da8ef2ad6afcf98c356fd19d91d74692171ad
     * Get address.
=======
     * Get address
>>>>>>> se agrego campo observaciones en usuario y se agrego a las instancias
     *
     * @return string $address
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
<<<<<<< a35da8ef2ad6afcf98c356fd19d91d74692171ad
     * Add orders.
=======
     * Add orders
>>>>>>> se agrego campo observaciones en usuario y se agrego a las instancias
     *
     * @param Celsius3\CoreBundle\Entity\Order $orders
     */
    public function addOrder(\Celsius3\CoreBundle\Entity\Order $orders)
    {
        $this->orders[] = $orders;
    }

    /**
<<<<<<< a35da8ef2ad6afcf98c356fd19d91d74692171ad
     * Remove orders.
=======
     * Remove orders
>>>>>>> se agrego campo observaciones en usuario y se agrego a las instancias
     *
     * @param Celsius3\CoreBundle\Entity\Order $orders
     */
    public function removeOrder(\Celsius3\CoreBundle\Entity\Order $orders)
    {
        $this->orders->removeElement($orders);
    }

    /**
<<<<<<< a35da8ef2ad6afcf98c356fd19d91d74692171ad
     * Get orders.
=======
     * Get orders
>>>>>>> se agrego campo observaciones en usuario y se agrego a las instancias
     *
     * @return Doctrine\Common\Collections\Collection $orders
     */
    public function getOrders()
    {
        return $this->orders;
    }

    /**
<<<<<<< a35da8ef2ad6afcf98c356fd19d91d74692171ad
     * Add operatedOrders.
=======
     * Add operatedOrders
>>>>>>> se agrego campo observaciones en usuario y se agrego a las instancias
     *
     * @param Celsius3\CoreBundle\Entity\Order $operatedOrders
     */
    public function addOperatedOrder(
    \Celsius3\CoreBundle\Entity\Order $operatedOrders)
    {
        $this->operatedOrders[] = $operatedOrders;
    }

    /**
<<<<<<< a35da8ef2ad6afcf98c356fd19d91d74692171ad
     * Remove operatedOrders.
=======
     * Remove operatedOrders
>>>>>>> se agrego campo observaciones en usuario y se agrego a las instancias
     *
     * @param Celsius3\CoreBundle\Entity\Order $operatedOrders
     */
    public function removeOperatedOrder(
    \Celsius3\CoreBundle\Entity\Order $operatedOrders)
    {
        $this->operatedOrders->removeElement($operatedOrders);
    }

    /**
<<<<<<< a35da8ef2ad6afcf98c356fd19d91d74692171ad
     * Get operatedOrders.
=======
     * Get operatedOrders
>>>>>>> se agrego campo observaciones en usuario y se agrego a las instancias
     *
     * @return Doctrine\Common\Collections\Collection $operatedOrders
     */
    public function getOperatedOrders()
    {
        return $this->operatedOrders;
    }

    /**
<<<<<<< a35da8ef2ad6afcf98c356fd19d91d74692171ad
     * Add createdOrders.
=======
     * Add createdOrders
>>>>>>> se agrego campo observaciones en usuario y se agrego a las instancias
     *
     * @param Celsius3\CoreBundle\Entity\Order $createdOrders
     */
    public function addCreatedOrder(
    \Celsius3\CoreBundle\Entity\Order $createdOrders)
    {
        $this->createdOrders[] = $createdOrders;
    }

    /**
<<<<<<< a35da8ef2ad6afcf98c356fd19d91d74692171ad
     * Remove createdOrders.
=======
     * Remove createdOrders
>>>>>>> se agrego campo observaciones en usuario y se agrego a las instancias
     *
     * @param Celsius3\CoreBundle\Entity\Order $createdOrders
     */
    public function removeCreatedOrder(
    \Celsius3\CoreBundle\Entity\Order $createdOrders)
    {
        $this->createdOrders->removeElement($createdOrders);
    }

    /**
<<<<<<< a35da8ef2ad6afcf98c356fd19d91d74692171ad
     * Get createdOrders.
=======
     * Get createdOrders
>>>>>>> se agrego campo observaciones en usuario y se agrego a las instancias
     *
     * @return Doctrine\Common\Collections\Collection $createdOrders
     */
    public function getCreatedOrders()
    {
        return $this->createdOrders;
    }

    /**
<<<<<<< a35da8ef2ad6afcf98c356fd19d91d74692171ad
     * Set instance.
     *
     * @param Celsius3\CoreBundle\Entity\Instance $instance
     *
=======
     * Set instance
     *
     * @param  Celsius3\CoreBundle\Entity\Instance $instance
>>>>>>> se agrego campo observaciones en usuario y se agrego a las instancias
     * @return self
     */
    public function setInstance(
    \Celsius3\CoreBundle\Entity\Instance $instance)
    {
        $this->instance = $instance;

        return $this;
    }

    /**
<<<<<<< a35da8ef2ad6afcf98c356fd19d91d74692171ad
     * Get instance.
=======
     * Get instance
>>>>>>> se agrego campo observaciones en usuario y se agrego a las instancias
     *
     * @return Celsius3\CoreBundle\Entity\Instance $instance
     */
    public function getInstance()
    {
        return $this->instance;
    }

    /**
<<<<<<< a35da8ef2ad6afcf98c356fd19d91d74692171ad
     * Set librarian.
     *
     * @param Celsius3\CoreBundle\Entity\BaseUser $librarian
     *
=======
     * Set librarian
     *
     * @param  Celsius3\CoreBundle\Entity\BaseUser $librarian
>>>>>>> se agrego campo observaciones en usuario y se agrego a las instancias
     * @return self
     */
    public function setLibrarian(\Celsius3\CoreBundle\Entity\BaseUser $librarian)
    {
        $this->librarian = $librarian;

        return $this;
    }

    /**
<<<<<<< a35da8ef2ad6afcf98c356fd19d91d74692171ad
     * Get librarian.
=======
     * Get librarian
>>>>>>> se agrego campo observaciones en usuario y se agrego a las instancias
     *
     * @return Celsius3\CoreBundle\Entity\BaseUser $librarian
     */
    public function getLibrarian()
    {
        return $this->librarian;
    }

    /**
<<<<<<< a35da8ef2ad6afcf98c356fd19d91d74692171ad
     * Set institution.
     *
     * @param Celsius3\CoreBundle\Entity\Institution $institution
     *
=======
     * Set institution
     *
     * @param  Celsius3\CoreBundle\Entity\Institution $institution
>>>>>>> se agrego campo observaciones en usuario y se agrego a las instancias
     * @return self
     */
    public function setInstitution(
    \Celsius3\CoreBundle\Entity\Institution $institution)
    {
        $this->institution = $institution;

        return $this;
    }

    /**
<<<<<<< a35da8ef2ad6afcf98c356fd19d91d74692171ad
     * Get institution.
=======
     * Get institution
>>>>>>> se agrego campo observaciones en usuario y se agrego a las instancias
     *
     * @return Celsius3\CoreBundle\Entity\Institution $institution
     */
    public function getInstitution()
    {
        return $this->institution;
    }

    /**
<<<<<<< a35da8ef2ad6afcf98c356fd19d91d74692171ad
     * Add customValues.
=======
     * Add customValues
>>>>>>> se agrego campo observaciones en usuario y se agrego a las instancias
     *
     * @param Celsius3\CoreBundle\Entity\CustomUserValue $customValues
     */
    public function addCustomValue(\Celsius3\CoreBundle\Entity\CustomUserValue $customValues)
    {
        $this->customValues[] = $customValues;
    }

    /**
<<<<<<< a35da8ef2ad6afcf98c356fd19d91d74692171ad
     * Remove customValues.
=======
     * Remove customValues
>>>>>>> se agrego campo observaciones en usuario y se agrego a las instancias
     *
     * @param Celsius3\CoreBundle\Entity\CustomUserValue $customValues
     */
    public function removeCustomValue(\Celsius3\CoreBundle\Entity\CustomUserValue $customValues)
    {
        $this->customValues->removeElement($customValues);
    }

    /**
<<<<<<< a35da8ef2ad6afcf98c356fd19d91d74692171ad
     * Get customValues.
=======
     * Get customValues
>>>>>>> se agrego campo observaciones en usuario y se agrego a las instancias
     *
     * @return Doctrine\Common\Collections\Collection $customValues
     */
    public function getCustomValues()
    {
        return $this->customValues;
    }

    /**
<<<<<<< a35da8ef2ad6afcf98c356fd19d91d74692171ad
     * Set downloadAuth.
     *
     * @param bool $downloadAuth
     *
=======
     * Set downloadAuth
     *
     * @param  boolean $downloadAuth
>>>>>>> se agrego campo observaciones en usuario y se agrego a las instancias
     * @return self
     */
    public function setDownloadAuth($downloadAuth)
    {
        $this->downloadAuth = $downloadAuth;

        return $this;
    }

    /**
<<<<<<< a35da8ef2ad6afcf98c356fd19d91d74692171ad
     * Get downloadAuth.
     *
     * @return bool $downloadAuth
=======
     * Get downloadAuth
     *
     * @return boolean $downloadAuth
>>>>>>> se agrego campo observaciones en usuario y se agrego a las instancias
     */
    public function getDownloadAuth()
    {
        return $this->downloadAuth;
    }

    /**
<<<<<<< a35da8ef2ad6afcf98c356fd19d91d74692171ad
     * Add secondaryInstance.
     *
     * @param Celsius3\CoreBundle\Entity\Instance $secondaryInstance
     * @param array                               $roles
=======
     * Add secondaryInstance
     *
     * @param Celsius3\CoreBundle\Entity\Instance $secondaryInstance
     * @param array $roles
>>>>>>> se agrego campo observaciones en usuario y se agrego a las instancias
     */
    public function addSecondaryInstance(\Celsius3\CoreBundle\Entity\Instance $secondaryInstance, array $roles)
    {
        $this->secondaryInstances[$secondaryInstance->getId()] = $roles;
    }

    /**
<<<<<<< a35da8ef2ad6afcf98c356fd19d91d74692171ad
     * Remove secondaryInstance.
=======
     * Remove secondaryInstance
>>>>>>> se agrego campo observaciones en usuario y se agrego a las instancias
     *
     * @param \Celsius3\CoreBundle\Entity\Instance $secondaryInstance
     */
    public function removeSecondaryInstance(\Celsius3\CoreBundle\Entity\Instance $secondaryInstance)
    {
        unset($this->secondaryInstances[$secondaryInstance->getId()]);
    }

    /**
<<<<<<< a35da8ef2ad6afcf98c356fd19d91d74692171ad
     * Get secondaryInstances.
=======
     * Get secondaryInstances
>>>>>>> se agrego campo observaciones en usuario y se agrego a las instancias
     *
     * @return array $secondaryInstances
     */
    public function getSecondaryInstances()
    {
        return $this->secondaryInstances;
    }

    /**
<<<<<<< a35da8ef2ad6afcf98c356fd19d91d74692171ad
     * Has secondaryInstances.
     *
     * @param \Celsius3\CoreBundle\Entity\Instance $secondaryInstance
     *
     * @return bool
=======
     * Has secondaryInstances
     *
     * @param \Celsius3\CoreBundle\Entity\Instance $secondaryInstance
     * @return boolean
>>>>>>> se agrego campo observaciones en usuario y se agrego a las instancias
     */
    public function hasSecondaryInstance(\Celsius3\CoreBundle\Entity\Instance $secondaryInstance)
    {
        return array_key_exists($secondaryInstance->getId(), $this->secondaryInstances);
    }

    public function getCountry()
    {
        if (!$this->getInstitution()) {
            return null;
        }

        return $this->getInstitution()->getCountry();
    }

    public function getCity()
    {
        if (!$this->getInstitution()) {
            return null;
        }

        return $this->getInstitution()->getCity();
    }

<<<<<<< a35da8ef2ad6afcf98c356fd19d91d74692171ad
    public function isLibrarian()
=======
    public function getIsLibrarian()
>>>>>>> se agrego campo observaciones en usuario y se agrego a las instancias
    {
        return in_array(UserManager::ROLE_LIBRARIAN, $this->getRoles());
    }

    /**
<<<<<<< a35da8ef2ad6afcf98c356fd19d91d74692171ad
     * Set wrongEmail.
     *
     * @param bool $wrongEmail
     *
=======
     * Set wrongEmail
     *
     * @param boolean $wrongEmail
>>>>>>> se agrego campo observaciones en usuario y se agrego a las instancias
     * @return self
     */
    public function setWrongEmail($wrongEmail)
    {
        $this->wrongEmail = $wrongEmail;
<<<<<<< a35da8ef2ad6afcf98c356fd19d91d74692171ad

=======
>>>>>>> se agrego campo observaciones en usuario y se agrego a las instancias
        return $this;
    }

    /**
<<<<<<< a35da8ef2ad6afcf98c356fd19d91d74692171ad
     * Get wrongEmail.
     *
     * @return bool $wrongEmail
=======
     * Get wrongEmail
     *
     * @return boolean $wrongEmail
>>>>>>> se agrego campo observaciones en usuario y se agrego a las instancias
     */
    public function getWrongEmail()
    {
        return $this->wrongEmail;
    }

    /**
<<<<<<< a35da8ef2ad6afcf98c356fd19d91d74692171ad
     * Set pdf.
     *
     * @param bool $pdf
     *
=======
     * Set pdf
     *
     * @param boolean $pdf
>>>>>>> se agrego campo observaciones en usuario y se agrego a las instancias
     * @return self
     */
    public function setPdf($pdf)
    {
        $this->pdf = $pdf;
<<<<<<< a35da8ef2ad6afcf98c356fd19d91d74692171ad

=======
>>>>>>> se agrego campo observaciones en usuario y se agrego a las instancias
        return $this;
    }

    /**
<<<<<<< a35da8ef2ad6afcf98c356fd19d91d74692171ad
     * Get pdf.
     *
     * @return bool $pdf
=======
     * Get pdf
     *
     * @return boolean $pdf
>>>>>>> se agrego campo observaciones en usuario y se agrego a las instancias
     */
    public function getPdf()
    {
        return $this->pdf;
    }

    /**
     * @param Client $client
<<<<<<< a35da8ef2ad6afcf98c356fd19d91d74692171ad
     *
     * @return bool
=======
     * @return boolean
>>>>>>> se agrego campo observaciones en usuario y se agrego a las instancias
     */
    public function isAuthorizedClient(Client $client)
    {
        return $this->clientApplications->contains($client);
    }

    /**
     * @param Client $client
     */
    public function addClientApplication(Client $client)
    {
        $this->clientApplications->add($client);
    }

    /**
     * @param Client $client
     */
    public function removeClientApplication(Client $client)
    {
        $this->clientApplications->removeElement($client);
    }

    /**
     * @return ArrayCollection
     */
    public function getClientApplications()
    {
        return $this->clientApplications;
    }

    public function getBaseInstitution()
    {
        return $this->getBaseInstitutionRec($this->getInstitution());
    }

    private function getBaseInstitutionRec(Institution $institution)
    {
        if (is_null($institution->getParent())) {
            return $institution;
        } else {
            return $this->getBaseInstitutionRec($institution->getParent());
        }
    }

    /**
<<<<<<< a35da8ef2ad6afcf98c356fd19d91d74692171ad
     * Set secondaryInstances.
=======
     * Set secondaryInstances
>>>>>>> se agrego campo observaciones en usuario y se agrego a las instancias
     *
     * @param array $secondaryInstances
     *
     * @return BaseUser
     */
    public function setSecondaryInstances($secondaryInstances)
    {
        $this->secondaryInstances = $secondaryInstances;

        return $this;
    }

    /**
<<<<<<< a35da8ef2ad6afcf98c356fd19d91d74692171ad
     * Add notificationSetting.
=======
     * Add notificationSetting
>>>>>>> se agrego campo observaciones en usuario y se agrego a las instancias
     *
     * @param \Celsius3\CoreBundle\Entity\Celsius3CoreBundle:NotificationSettings $notificationSetting
     *
     * @return BaseUser
     */
    public function addNotificationSetting(\Celsius3\NotificationBundle\Entity\NotificationSettings $notificationSetting)
    {
        $this->notificationSettings[] = $notificationSetting;

        return $this;
    }

    /**
<<<<<<< a35da8ef2ad6afcf98c356fd19d91d74692171ad
     * Remove notificationSetting.
=======
     * Remove notificationSetting
>>>>>>> se agrego campo observaciones en usuario y se agrego a las instancias
     *
     * @param \Celsius3\CoreBundle\Entity\Celsius3CoreBundle:NotificationSettings $notificationSetting
     */
    public function removeNotificationSetting(\Celsius3\NotificationBundle\Entity\NotificationSettings $notificationSetting)
    {
        $this->notificationSettings->removeElement($notificationSetting);
    }

    /**
<<<<<<< a35da8ef2ad6afcf98c356fd19d91d74692171ad
     * Get notificationSettings.
=======
     * Get notificationSettings
>>>>>>> se agrego campo observaciones en usuario y se agrego a las instancias
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getNotificationSettings()
    {
        return $this->notificationSettings;
    }

    /**
<<<<<<< a35da8ef2ad6afcf98c356fd19d91d74692171ad
     * Add notification.
=======
     * Add notification
>>>>>>> se agrego campo observaciones en usuario y se agrego a las instancias
     *
     * @param Celsius3\NotificationBundle\Entity\BaseUserNotification $notification
     */
    public function addNotification(\Celsius3\NotificationBundle\Entity\BaseUserNotification $notification)
    {
        $this->notifications[] = $notification;
    }

    /**
<<<<<<< a35da8ef2ad6afcf98c356fd19d91d74692171ad
     * Remove notification.
=======
     * Remove notification
>>>>>>> se agrego campo observaciones en usuario y se agrego a las instancias
     *
     * @param Celsius3\NotificationBundle\Entity\BaseUserNotification $notification
     */
    public function removeNotification(\Celsius3\NotificationBundle\Entity\BaseUserNotification $notification)
    {
        $this->notifications->removeElement($notification);
    }

    /**
<<<<<<< a35da8ef2ad6afcf98c356fd19d91d74692171ad
     * Get notifications.
=======
     * Get notifications
>>>>>>> se agrego campo observaciones en usuario y se agrego a las instancias
     *
     * @return Doctrine\Common\Collections\Collection $customValues
     */
    public function getNotifications()
    {
        return $this->notifications;
    }

    /**
<<<<<<< a35da8ef2ad6afcf98c356fd19d91d74692171ad
     * Add librarianInstitution.
=======
     * Add librarianInstitution
>>>>>>> se agrego campo observaciones en usuario y se agrego a las instancias
     *
     * @param \Celsius3\CoreBundle\Entity\Institution $librarianInstitution
     *
     * @return BaseUser
     */
    public function addLibrarianInstitution(\Celsius3\CoreBundle\Entity\Institution $librarianInstitution)
    {
        $this->librarianInstitution[] = $librarianInstitution;

        return $this;
    }

    /**
<<<<<<< a35da8ef2ad6afcf98c356fd19d91d74692171ad
     * Remove librarianInstitution.
=======
     * Remove librarianInstitution
>>>>>>> se agrego campo observaciones en usuario y se agrego a las instancias
     *
     * @param \Celsius3\CoreBundle\Entity\Institution $librarianInstitution
     */
    public function removeLibrarianInstitution(\Celsius3\CoreBundle\Entity\Institution $librarianInstitution)
    {
        $this->librarianInstitution->removeElement($librarianInstitution);
    }

    /**
<<<<<<< a35da8ef2ad6afcf98c356fd19d91d74692171ad
     * Get librarianInstitution.
=======
     * Get librarianInstitution
>>>>>>> se agrego campo observaciones en usuario y se agrego a las instancias
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLibrarianInstitution()
    {
        return $this->librarianInstitution;
    }

    /**
<<<<<<< a35da8ef2ad6afcf98c356fd19d91d74692171ad
     * Set observaciones.
=======
     * Set observaciones
>>>>>>> se agrego campo observaciones en usuario y se agrego a las instancias
     *
     * @param string $observaciones
     *
     * @return BaseUser
     */
    public function setObservaciones($observaciones)
    {
        $this->observaciones = $observaciones;

        return $this;
    }

    /**
<<<<<<< a35da8ef2ad6afcf98c356fd19d91d74692171ad
     * Get observaciones.
=======
     * Get observaciones
>>>>>>> se agrego campo observaciones en usuario y se agrego a las instancias
     *
     * @return string
     */
    public function getObservaciones()
    {
        return $this->observaciones;
    }

}
