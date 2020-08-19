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

use Celsius3\ApiBundle\Entity\Client;
use Celsius3\CoreBundle\Manager\UserManager;
use Celsius3\NotificationBundle\Entity\BaseUserNotification;
use Celsius3\NotificationBundle\Entity\Notifiable;
use Celsius3\NotificationBundle\Entity\NotificationSettings;
use Celsius3\NotificationBundle\Manager\NotificationManager;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use FOS\MessageBundle\Model\ParticipantInterface;
use FOS\UserBundle\Model\User;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;
use Symfony\Component\Validator\Constraints as Assert;

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
 * @DoctrineAssert\UniqueEntity("username")
 * @DoctrineAssert\UniqueEntity("email")
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
     * @Assert\Email(
     *     strict = true,
     *     checkMX = true,
     *     checkHost = true,
     *     groups = {"Default"}
     * )
     */
    protected $email;

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
    protected $downloadAuth = false;

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
     * @Assert\NotNull()
     * @ORM\ManyToOne(targetEntity="Instance", inversedBy="users")
     * @ORM\JoinColumn(name="instance_id", referencedColumnName="id", nullable=false)
     */
    protected $instance;

    /**
     * @Assert\NotNull()
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
    //
    // /**
    //  * @Recaptcha\IsTrue
    //  */
    // public $recaptcha;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $locked = false;

    public function __toString()
    {
        return ucwords(strtolower($this->getSurname())) . ', ' . ucwords(strtolower($this->getName()));
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
        $this->orders = new ArrayCollection();
        $this->operatedOrders = new ArrayCollection();
        $this->createdOrders = new ArrayCollection();
        $this->customValues = new ArrayCollection();
        $this->clientApplications = new ArrayCollection();
        $this->notifications = new ArrayCollection();
        $this->librarianInstitution = new ArrayCollection();
    }

    /**
     * Get id.
     *
     * @return id $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string $name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set surname.
     *
     * @param string $surname
     *
     * @return self
     */
    public function setSurname($surname)
    {
        $this->surname = $surname;

        return $this;
    }

    /**
     * Get surname.
     *
     * @return string $surname
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * Set birthdate.
     *
     * @param date $birthdate
     *
     * @return self
     */
    public function setBirthdate($birthdate)
    {
        $this->birthdate = $birthdate;

        return $this;
    }

    /**
     * Get birthdate.
     *
     * @return date $birthdate
     */
    public function getBirthdate()
    {
        return $this->birthdate;
    }

    /**
     * Set address.
     *
     * @param string $address
     *
     * @return self
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address.
     *
     * @return string $address
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Add orders.
     *
     * @param Order $orders
     */
    public function addOrder(Order $orders)
    {
        $this->orders[] = $orders;
    }

    /**
     * Remove orders.
     *
     * @param Order $orders
     */
    public function removeOrder(Order $orders)
    {
        $this->orders->removeElement($orders);
    }

    /**
     * Get orders.
     *
     * @return Collection $orders
     */
    public function getOrders()
    {
        return $this->orders;
    }

    /**
     * Add operated order.
     *
     * @param Order $operatedOrder
     */
    public function addOperatedOrder(Order $operatedOrder)
    {
        $this->operatedOrders[] = $operatedOrder;
    }

    /**
     * Remove operated order.
     *
     * @param Order $operatedOrder
     */
    public function removeOperatedOrder(Order $operatedOrder)
    {
        $this->operatedOrders->removeElement($operatedOrder);
    }

    /**
     * Get operated orders.
     *
     * @return Collection $operatedOrders
     */
    public function getOperatedOrders()
    {
        return $this->operatedOrders;
    }

    /**
     * Add created order.
     *
     * @param Order $createdOrder
     */
    public function addCreatedOrder(Order $createdOrder)
    {
        $this->createdOrders[] = $createdOrder;
    }

    /**
     * Remove created order.
     *
     * @param Order $createdOrder
     */
    public function removeCreatedOrder(Order $createdOrder)
    {
        $this->createdOrders->removeElement($createdOrder);
    }

    /**
     * Get created orders.
     *
     * @return Collection $createdOrders
     */
    public function getCreatedOrders()
    {
        return $this->createdOrders;
    }

    /**
     * Set instance.
     *
     * @param Instance $instance
     *
     * @return self
     */
    public function setInstance(Instance $instance)
    {
        $this->instance = $instance;

        return $this;
    }

    /**
     * Get instance.
     *
     * @return Instance $instance
     */
    public function getInstance()
    {
        return $this->instance;
    }

    /**
     * Set librarian.
     *
     * @param BaseUser $librarian
     *
     * @return self
     */
    public function setLibrarian(BaseUser $librarian)
    {
        $this->librarian = $librarian;

        return $this;
    }

    /**
     * Get librarian.
     *
     * @return BaseUser $librarian
     */
    public function getLibrarian()
    {
        return $this->librarian;
    }

    /**
     * Set institution.
     *
     * @param Institution $institution
     *
     * @return self
     */
    public function setInstitution(Institution $institution)
    {
        $this->institution = $institution;

        return $this;
    }

    /**
     * Get institution.
     *
     * @return Institution $institution
     */
    public function getInstitution()
    {
        return $this->institution;
    }

    /**
     * Add customValues.
     *
     * @param CustomUserValue $customValues
     */
    public function addCustomValue(CustomUserValue $customValues)
    {
        $this->customValues[] = $customValues;
    }

    /**
     * Remove customValues.
     *
     * @param CustomUserValue $customValues
     */
    public function removeCustomValue(CustomUserValue $customValues)
    {
        $this->customValues->removeElement($customValues);
    }

    /**
     * Get customValues.
     *
     * @return Collection $customValues
     */
    public function getCustomValues()
    {
        return $this->customValues;
    }

    /**
     * Set downloadAuth.
     *
     * @param bool $downloadAuth
     *
     * @return self
     */
    public function setDownloadAuth($downloadAuth)
    {
        $this->downloadAuth = $downloadAuth;

        return $this;
    }

    /**
     * Get downloadAuth.
     *
     * @return bool $downloadAuth
     */
    public function getDownloadAuth()
    {
        return $this->downloadAuth;
    }

    /**
     * Add secondary instance.
     *
     * @param Instance $secondaryInstance
     * @param array $roles
     */
    public function addSecondaryInstance(Instance $secondaryInstance, array $roles)
    {
        $this->secondaryInstances[(int)$secondaryInstance->getId()] = $roles;
    }

    /**
     * Remove secondary instance.
     *
     * @param Instance $secondaryInstance
     */
    public function removeSecondaryInstance(Instance $secondaryInstance)
    {
        unset($this->secondaryInstances[(int)$secondaryInstance->getId()]);
    }

    /**
     * Get secondary instances.
     *
     * @return array $secondaryInstances
     */
    public function getSecondaryInstances()
    {
        return $this->secondaryInstances;
    }

    /**
     * Has secondary instances.
     *
     * @param Instance $secondaryInstance
     *
     * @return bool
     */
    public function hasSecondaryInstance(Instance $secondaryInstance)
    {
        return array_key_exists($secondaryInstance->getId(), $this->secondaryInstances);
    }

    /**
     * Get country.
     *
     * @return Country|null
     */
    public function getCountry()
    {
        if (!$this->getInstitution()) {
            return null;
        }

        return $this->getInstitution()->getCountry();
    }

    /**
     * Get city.
     *
     * @return City|null
     */
    public function getCity()
    {
        if (!$this->getInstitution()) {
            return null;
        }

        return $this->getInstitution()->getCity();
    }

    /**
     * Is librarian.
     *
     * @return bool
     */
    public function isLibrarian()
    {
        return in_array(UserManager::ROLE_LIBRARIAN, $this->getRoles());
    }

    /**
     * Set wrong email.
     *
     * @param bool $wrongEmail
     *
     * @return self
     */
    public function setWrongEmail($wrongEmail)
    {
        $this->wrongEmail = $wrongEmail;

        return $this;
    }

    /**
     * Get wrong email.
     *
     * @return bool $wrongEmail
     */
    public function getWrongEmail()
    {
        return $this->wrongEmail;
    }

    /**
     * Set pdf.
     *
     * @param bool $pdf
     *
     * @return self
     */
    public function setPdf($pdf)
    {
        $this->pdf = $pdf;

        return $this;
    }

    /**
     * Get pdf.
     *
     * @return bool $pdf
     */
    public function getPdf()
    {
        return $this->pdf;
    }

    /**
     * Is authorized client.
     *
     * @param Client $client
     *
     * @return bool
     */
    public function isAuthorizedClient(Client $client)
    {
        return $this->clientApplications->contains($client);
    }

    /**
     * Add client application.
     *
     * @param Client $client
     */
    public function addClientApplication(Client $client)
    {
        $this->clientApplications->add($client);
    }

    /**
     * Remove client application.
     *
     * @param Client $client
     */
    public function removeClientApplication(Client $client)
    {
        $this->clientApplications->removeElement($client);
    }

    /**
     * Get client application.
     *
     * @return ArrayCollection
     */
    public function getClientApplications()
    {
        return $this->clientApplications;
    }

    /**
     * Get base institution.
     *
     * @return Institution
     */
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
     * Set secondary instances.
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
     * Add notification setting.
     *
     * @param NotificationSettings $notificationSetting
     *
     * @return BaseUser
     */
    public function addNotificationSetting(NotificationSettings $notificationSetting)
    {
        $this->notificationSettings[] = $notificationSetting;

        return $this;
    }

    /**
     * Remove notification setting.
     *
     * @param NotificationSettings $notificationSetting
     */
    public function removeNotificationSetting(NotificationSettings $notificationSetting)
    {
        $this->notificationSettings->removeElement($notificationSetting);
    }

    /**
     * Get notification settings.
     *
     * @return Collection $notificationSettings
     */
    public function getNotificationSettings()
    {
        return $this->notificationSettings;
    }

    /**
     * Add notification.
     *
     * @param BaseUserNotification $notification
     */
    public function addNotification(BaseUserNotification $notification)
    {
        $this->notifications[] = $notification;
    }

    /**
     * Remove notification.
     *
     * @param BaseUserNotification $notification
     */
    public function removeNotification(BaseUserNotification $notification)
    {
        $this->notifications->removeElement($notification);
    }

    /**
     * Get notifications.
     *
     * @return Collection $notifications
     */
    public function getNotifications()
    {
        return $this->notifications;
    }

    /**
     * Add librarian institution.
     *
     * @param Institution $librarianInstitution
     *
     * @return BaseUser
     */
    public function addLibrarianInstitution(Institution $librarianInstitution)
    {
        $this->librarianInstitution[] = $librarianInstitution;

        return $this;
    }

    /**
     * Remove librarian institution.
     *
     * @param Institution $librarianInstitution
     */
    public function removeLibrarianInstitution(Institution $librarianInstitution)
    {
        $this->librarianInstitution->removeElement($librarianInstitution);
    }

    /**
     * Get librarian institution.
     *
     * @return Collection $librarianInstitution
     */
    public function getLibrarianInstitution()
    {
        return $this->librarianInstitution;
    }

    /**
     * Set observaciones.
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
     * Get observaciones.
     *
     * @return string
     */
    public function getObservaciones()
    {
        return $this->observaciones;
    }

    public function setLocked($locked)
    {
        $this->locked = $locked;
    }

    public function isLocked()
    {
        return $this->locked;
    }
}
