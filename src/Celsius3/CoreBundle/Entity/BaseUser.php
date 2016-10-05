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
use Gedmo\Mapping\Annotation as Gedmo;
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
$this->orders = new \Doctrine\Common\Collections\ArrayCollection();
$this->operatedOrders = new \Doctrine\Common\Collections\ArrayCollection();
$this->createdOrders = new \Doctrine\Common\Collections\ArrayCollection();
$this->customValues = new \Doctrine\Common\Collections\ArrayCollection();
$this->clientApplications = new \Doctrine\Common\Collections\ArrayCollection();
$this->notifications = new \Doctrine\Common\Collections\ArrayCollection();
$this->librarianInstitution = new \Doctrine\Common\Collections\ArrayCollection();
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
public function setLibrarian(\Celsius3\CoreBundle\Entity\BaseUser $librarian)
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
 * Add secondaryInstance
 *
 * @param Celsius3\CoreBundle\Entity\Instance $secondaryInstance
 * @param array $roles
 */
public function addSecondaryInstance(\Celsius3\CoreBundle\Entity\Instance $secondaryInstance, array $roles)
{
$this->secondaryInstances[$secondaryInstance->getId()] = $roles;
}

/**
 * Remove secondaryInstance
 *
 * @param \Celsius3\CoreBundle\Entity\Instance $secondaryInstance
 */
public function removeSecondaryInstance(\Celsius3\CoreBundle\Entity\Instance $secondaryInstance)
{
unset($this->secondaryInstances[$secondaryInstance->getId()]);
}

/**
 * Get secondaryInstances
 *
 * @return array $secondaryInstances
 */
public function getSecondaryInstances()
{
return $this->secondaryInstances;
}

/**
 * Has secondaryInstances
 *
 * @param \Celsius3\CoreBundle\Entity\Instance $secondaryInstance
 * @return boolean
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

/**
 * Set pdf
 *
 * @param boolean $pdf
 * @return self
 */
public function setPdf($pdf)
{
$this->pdf = $pdf;
return $this;
}

/**
 * Get pdf
 *
 * @return boolean $pdf
 */
public function getPdf()
{
return $this->pdf;
}

/**
 * @param Client $client
 * @return boolean
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
 * Set secondaryInstances
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
 * Add notificationSetting
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
 * Remove notificationSetting
 *
 * @param \Celsius3\CoreBundle\Entity\Celsius3CoreBundle:NotificationSettings $notificationSetting
 */
public function removeNotificationSetting(\Celsius3\NotificationBundle\Entity\NotificationSettings $notificationSetting)
{
$this->notificationSettings->removeElement($notificationSetting);
}

/**
 * Get notificationSettings
 *
 * @return \Doctrine\Common\Collections\Collection
 */
public function getNotificationSettings()
{
return $this->notificationSettings;
}

/**
 * Add notification
 *
 * @param Celsius3\NotificationBundle\Entity\BaseUserNotification $notification
 */
public function addNotification(\Celsius3\NotificationBundle\Entity\BaseUserNotification $notification)
{
$this->notifications[] = $notification;
}

/**
 * Remove notification
 *
 * @param Celsius3\NotificationBundle\Entity\BaseUserNotification $notification
 */
public function removeNotification(\Celsius3\NotificationBundle\Entity\BaseUserNotification $notification)
{
$this->notifications->removeElement($notification);
}

/**
 * Get notifications
 *
 * @return Doctrine\Common\Collections\Collection $customValues
 */
public function getNotifications()
{
return $this->notifications;
}

/**
 * Add librarianInstitution
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
 * Remove librarianInstitution
 *
 * @param \Celsius3\CoreBundle\Entity\Institution $librarianInstitution
 */
public function removeLibrarianInstitution(\Celsius3\CoreBundle\Entity\Institution $librarianInstitution)
{
$this->librarianInstitution->removeElement($librarianInstitution);
}

/**
 * Get librarianInstitution
 *
 * @return \Doctrine\Common\Collections\Collection
 */
public function getLibrarianInstitution()
{
return $this->librarianInstitution;
}

/**
 * Set observaciones
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
 * Get observaciones
 *
 * @return string
 */
public function getObservaciones()
{
return $this->observaciones;
}

}
