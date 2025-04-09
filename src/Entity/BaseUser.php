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

namespace Celsius3\Entity;

use Celsius3\Entity\Mixin\TimestampableEntity;
use Celsius3\Manager\NotificationManager;
use Celsius3\Manager\UserManager;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;


#[ORM\Entity(repositoryClass: \Celsius3\Repository\BaseUserRepository::class)]
#[ORM\Table(name: "user", indexes: [
    new ORM\Index(name: "idx_username", columns: ["username"]),
    new ORM\Index(name: "idx_email", columns: ["email"]),
    new ORM\Index(name: "idx_name", columns: ["name"]),
    new ORM\Index(name: "idx_surname", columns: ["surname"]),
    new ORM\Index(name: "idx_enabled", columns: ["enabled"]),
    new ORM\Index(name: "idx_locked", columns: ["locked"]),
    new ORM\Index(name: "idx_instance", columns: ["instance_id"]),
    new ORM\Index(name: "idx_institution", columns: ["institution_id"])
])]
#[ORM\HasLifecycleCallbacks]
#[DoctrineAssert\UniqueEntity("username")]
#[DoctrineAssert\UniqueEntity("email")]
class BaseUser implements  UserInterface, PasswordAuthenticatedUserInterface, Notifiable
{
    use TimestampableEntity;

    #[ORM\Column(type: "integer")]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    #[Groups([
        "api",
        "administration",
        "administration_list",
        "administration_order_show",
        "administration_user_show",
        "user_list",
        "admins-select"
    ])]
    protected ?int $id = null;

    #[ORM\Column(type: "string", length: 180, nullable: true)]
    private ?string $confirmationToken = null;

    private const TOKEN_LIFETIME = 2; // días que dura la validez del token
    private const CIPHER_ALGO = 'aes-256-cbc'; // Encryption algorithm

    #[Assert\Email(groups: ["Default"])]
    #[ORM\Column(type: "string", length: 180, unique: true)]
    #[Groups(["ajax_list"])]
    protected ?string $email = null;

    #[ORM\Column(type: "string", unique: true)]
    #[Groups(["ajax_list_name"])]
    private ?string $username = null;

    #[ORM\Column(type: "string")]
    private ?string $username_canonical = null;

    #[ORM\Column(type: "string")]
    private ?string $email_canonical = null;

    #[ORM\Column(type: "array")]
    private array $roles = [];

    #[ORM\Column(type: "string")]
    private ?string $password = null;

    #[ORM\Column(type: "string")]
    private ?string $salt = null;

    #[ORM\Column(type: "boolean")]
    #[Groups([
        "api",
        "administration"
    ])]
    private bool $enabled = false;

    #[Assert\NotBlank(groups: ["Default"])]
    #[ORM\Column(type: "string", length: 255)]
    #[Groups([
        "api",
        "administration",
        "administration_list",
        "administration_order_show",
        "administration_user_show",
        "user_list",
        "admins-select",
        "email_template",
        "ajax_list"
    ])]
    protected ?string $name = null;

    #[Assert\NotBlank(groups: ["Default"])]
    #[ORM\Column(type: "string", length: 255)]
    #[Groups([
        "api",
        "administration",
        "administration_list",
        "administration_order_show",
        "administration_user_show",
        "user_list",
        "admins-select",
        "email_template",
        "ajax_list"
    ])]
    protected ?string $surname = null;

    #[Assert\Date(groups: ["Default"])]
    #[ORM\Column(type: "date", nullable: true)]
    #[Groups(["administration"])]
    protected ?\DateTime $birthdate = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    #[Groups(["administration"])]
    protected ?string $address = null;

    #[Assert\NotNull]
    #[Assert\Type(type: "boolean")]
    #[ORM\Column(type: "boolean")]
    #[Groups([
        "api",
        "user_list"
    ])]
    protected bool $downloadAuth = true;

    #[Assert\NotNull]
    #[Assert\Type(type: "boolean")]
    #[ORM\Column(type: "boolean")]
    #[Groups([
        "api",
        "administration_list",
        "administration_order_show",
        "administration_user_show",
        "user_list"
    ])]
    protected bool $wrongEmail = false;

    #[Assert\NotNull]
    #[Assert\Type(type: "boolean")]
    #[ORM\Column(type: "boolean")]
    #[Groups([
        "api",
        "administration_list",
        "administration_order_show",
        "administration_user_show",
        "user_list"
    ])]
    protected bool $pdf = true;

    #[ORM\OneToMany(
        targetEntity: Request::class,
        mappedBy: "owner",
        fetch: "EXTRA_LAZY"
    )]
    protected Collection $orders;

    #[ORM\OneToMany(
        targetEntity: Request::class,
        mappedBy: "operator",
        fetch: "EXTRA_LAZY"
    )]
    protected Collection $operatedOrders;

    #[ORM\OneToMany(
        targetEntity: Request::class,
        mappedBy: "creator",
        fetch: "EXTRA_LAZY"
    )]
    protected Collection $createdOrders;

    #[Assert\NotNull]
    #[ORM\ManyToOne(
        targetEntity: Instance::class,
        inversedBy: "users",
        cascade: ["persist"]
    )]
    #[ORM\JoinColumn(name: "instance_id", referencedColumnName: "id", nullable: false)]
    protected ?Instance $instance = null;

    #[Assert\NotNull]
    #[ORM\ManyToOne(targetEntity: Institution::class, inversedBy: "users")]
    #[ORM\JoinColumn(name: "institution_id", referencedColumnName: "id", nullable: false)]
    #[Groups([
        "administration",
        "administration_list",
        "administration_order_show",
        "administration_user_show"
    ])]
    protected ?Institution $institution = null;

    #[ORM\Column(type: "array", name: "secondary_instances")]
    protected array $secondaryInstances = [];

    #[ORM\OneToMany(
        targetEntity: CustomUserValue::class,
        mappedBy: "user",
        cascade: ["remove"], 
        fetch: "EXTRA_LAZY"
    )]
    #[Groups(["administration"])]
    protected Collection $customValues;

    #[ORM\ManyToMany(targetEntity: Client::class)]
    #[ORM\JoinTable(name: "user_client",
        joinColumns: [
            new ORM\JoinColumn(name: "user_id", referencedColumnName: "id")
        ],
        inverseJoinColumns: [
            new ORM\JoinColumn(name: "client_id", referencedColumnName: "id")
        ]
    )]
    protected Collection $clientApplications;

    #[ORM\OneToMany(
        targetEntity: NotificationSettings::class,
        mappedBy: "user",
        cascade: ["remove"],
        fetch: "EXTRA_LAZY"
    )]
    protected Collection $notificationSettings;

    #[ORM\OneToMany(
        targetEntity: BaseUserNotification::class,
        mappedBy: "object",
        cascade: ["remove"],
        fetch: "EXTRA_LAZY"
    )]
    protected Collection $notifications;

    #[ORM\ManyToMany(targetEntity: Institution::class , inversedBy: "librarian")]
    #[ORM\JoinTable(name: "librarian_institution",
        joinColumns: [
            new ORM\JoinColumn(name: "user_id", referencedColumnName: "id")
        ],
        inverseJoinColumns: [
            new ORM\JoinColumn(name: "institution_id", referencedColumnName: "id")
        ]
    )]
    protected Collection $librarianInstitution;

    #[ORM\Column(type: "string", nullable: true)]
    protected ?string $observaciones = null;

    #[ORM\Column(type: "boolean")]
    protected bool $locked = false;

    public function __construct()
    {
        $this->orders = new ArrayCollection();
        $this->operatedOrders = new ArrayCollection();
        $this->createdOrders = new ArrayCollection();
        $this->customValues = new ArrayCollection();
        $this->clientApplications = new ArrayCollection();
        $this->notifications = new ArrayCollection();
        $this->librarianInstitution = new ArrayCollection();
    }

    public function getConfirmationToken(): ?string
    { return $this->confirmationToken; }

    public function cleanConfirmationToken(): static
    {
        $this->confirmationToken = null;
        return $this;
    }

    private function getEncryptionKey(): string
    { return $this->getPassword(); }

    private function encryptToken(string $data, string $encryptionKey): string
    {
        $iv = random_bytes(
            openssl_cipher_iv_length(self::CIPHER_ALGO)
        );

        $encrypted = openssl_encrypt(
            $data,
            self::CIPHER_ALGO,
            $encryptionKey,
            0,
            $iv
        );

        return strtr(
            base64_encode((string) $iv . $encrypted),
            '+/', '-_'
        );
        // return base64_encode((string) $iv . $encrypted);
    }

    public function generateConfirmationToken(string $pass): static
    {
        $timestamp = (new \DateTime())->getTimestamp();
        $encryptedToken = $this->encryptToken($timestamp, $pass);
        $this->confirmationToken = $encryptedToken;
        return $this;
    }

    private function decryptToken(string $base64EncData, string $encryptionKey): ?string
    {
        $encryptedData = base64_decode(
            strtr($base64EncData, '-_', '+/')
        );
        // $encryptedData = base64_decode($base64EncData);
        $ivLength = openssl_cipher_iv_length(self::CIPHER_ALGO);
        $iv = substr($encryptedData, 0, $ivLength);
        $encrypted = substr($encryptedData, $ivLength);

        $decrypted = openssl_decrypt(
            $encrypted,
            self::CIPHER_ALGO,
            $encryptionKey,
            0,
            $iv
        );

        if ($decrypted === false) return null;
        return $decrypted;
    }

    public function isConfirmationTokenValid(
        string $encryptedData, string $encryptionKey
    ): bool {
        $strTimestamp = $this->decryptToken(
            $encryptedData, $encryptionKey
        );

        if (null === $strTimestamp) return false; // Token is invalid or decryption failed
        $timestamp = (new \DateTime())->setTimestamp($strTimestamp);

        $now = new \DateTime();
        $interval = $now->diff($timestamp);
        $days = $interval->days;

        return $days <= self::TOKEN_LIFETIME;
    }

    public function __toString() : string
    {
        return ucwords(
            strtolower($this->getSurname())
        )
        . ', '
        . ucwords(strtolower($this->getName()));
    }

    public function getId(): ?int
    { return $this->id; }

    public function getEmail(): ?string
    { return $this->email; }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        $this->email_canonical = $email;

        return $this;
    }

    public function getUsername(): ?string
    { return $this->username; }

    public function setUsername(string $username): self
    {
        $this->username = $username;
        $this->username_canonical = $username;

        return $this;
    }

    public function getUserIdentifier(): string
    { return (string) $this->email; }

    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getPassword(): ?string
    { return $this->password; }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getSalt(): ?string
    { return $this->salt; }

    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getSurname(): ?string
    { return $this->surname; }

    public function setSurname(string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }

    public function getName(): ?string
    { return $this->name; }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    #[Groups(["email_template"])]
    public function getFullName(): string
    { return $this->getSurname() . ', ' . $this->getName(); }

    public function full_name(): string
    { return $this->getFullName(); }

    public function notify(NotificationManager $manager): void
    {
        $manager->notifyNewUser($this);
    }

    #[ORM\PrePersist]
    public function prePersist(): void
    {
        $this->addRole(UserManager::ROLE_USER);
    }

    public function getBirthdate(): ?\DateTime
    { return $this->birthdate; }

    public function setBirthdate(\DateTime $birthdate): self
    {
        $this->birthdate = $birthdate;

        return $this;
    }

    public function getAddress(): ?string
    { return $this->address; }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function addOrder(Order $orders): void
    {
        $this->orders[] = $orders;
    }

    public function removeOrder(Order $orders): void
    {
        $this->orders->removeElement($orders);
    }

    public function getOrders(): Collection
    { return $this->orders; }

    public function addOperatedOrder(Order $operatedOrder): void
    {
        $this->operatedOrders[] = $operatedOrder;
    }

    public function removeOperatedOrder(Order $operatedOrder): void
    {
        $this->operatedOrders->removeElement($operatedOrder);
    }

    public function getOperatedOrders(): Collection
    { return $this->operatedOrders; }

    public function addCreatedOrder(Order $createdOrder): void
    {
        $this->createdOrders[] = $createdOrder;
    }

    public function removeCreatedOrder(Order $createdOrder): void
    {
        $this->createdOrders->removeElement($createdOrder);
    }

    public function getCreatedOrders(): Collection
    { return $this->createdOrders; }

    public function getInstance(): ?Instance
    { return $this->instance; }

    public function setInstance(Instance $instance): self
    {
        $this->instance = $instance;

        return $this;
    }

    public function addCustomValue(CustomValue $customValues): void
    {
        $this->customValues[] = $customValues;
    }

    public function removeCustomValue(CustomValue $customValues): void
    {
        $this->customValues->removeElement($customValues);
    }

    public function getCustomValues(): Collection
    { return $this->customValues; }

    public function getDownloadAuth(): bool
    { return $this->downloadAuth; }

    public function setDownloadAuth(bool $downloadAuth): self
    {
        $this->downloadAuth = $downloadAuth;

        return $this;
    }

    public function addSecondaryInstance(Instance $secondaryInstance, array $roles): void
    {
        $this->secondaryInstances[(int)$secondaryInstance->getId()] = $roles;
    }

    public function removeSecondaryInstance(Instance $secondaryInstance): void
    {
        unset($this->secondaryInstances[(int)$secondaryInstance->getId()]);
    }

    public function getSecondaryInstances(): array
    { return $this->secondaryInstances; }

    public function setSecondaryInstances(array $secondaryInstances): self
    {
        $this->secondaryInstances = $secondaryInstances;

        return $this;
    }

    public function hasSecondaryInstance(Instance $secondaryInstance): bool
    { return array_key_exists($secondaryInstance->getId(), $this->secondaryInstances); }

    #[Groups(["administration"])]
    public function getCountry()
    {
        if (!$this->getInstitution()) {
            return null;
        }

        return $this->getInstitution()->getCountry();
    }

    public function getInstitution(): ?Institution
    { return $this->institution; }

    public function setInstitution(Institution $institution): self
    {
        $this->institution = $institution;

        return $this;
    }

    #[Groups(["administration"])]
    public function getCity()
    {
        if (!$this->getInstitution()) {
            return null;
        }

        return $this->getInstitution()->getCity();
    }

    #[Groups([
        "api",
        "user_list"
    ])]
    public function isLibrarian(): bool
    { return in_array(UserManager::ROLE_LIBRARIAN, $this->getRoles()); }

    public function getWrongEmail(): bool
    { return $this->wrongEmail; }

    public function setWrongEmail(bool $wrongEmail): self
    {
        $this->wrongEmail = $wrongEmail;

        return $this;
    }

    public function getPdf(): bool
    { return $this->pdf; }

    public function setPdf(bool $pdf): self
    {
        $this->pdf = $pdf;

        return $this;
    }

    public function isAuthorizedClient(Client $client): bool
    { return $this->clientApplications->contains($client); }

    public function addClientApplication(Client $client): void
    { $this->clientApplications->add($client); }

    public function removeClientApplication(Client $client): void
    { $this->clientApplications->removeElement($client); }

    public function getClientApplications(): Collection
    { return $this->clientApplications; }

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

    public function addNotificationSetting(NotificationSettings $notificationSetting): self
    {
        $this->notificationSettings[] = $notificationSetting;

        return $this;
    }

    public function removeNotificationSetting(NotificationSettings $notificationSetting): void
    {
        $this->notificationSettings->removeElement($notificationSetting);
    }

    public function getNotificationSettings(): Collection
    { return $this->notificationSettings; }

    public function addNotification(BaseUserNotification $notification): void
    {
        $this->notifications[] = $notification;
    }

    public function removeNotification(BaseUserNotification $notification): void
    {
        $this->notifications->removeElement($notification);
    }

    public function getNotifications(): Collection
    { return $this->notifications; }

    public function addLibrarianInstitution(Institution $librarianInstitution): self
    {
        $this->librarianInstitution[] = $librarianInstitution;

        return $this;
    }

    public function removeLibrarianInstitution(
        Institution $librarianInstitution
    ): void {
        $this->librarianInstitution->removeElement($librarianInstitution);
    }

    public function getLibrarianInstitution(): Collection
    { return $this->librarianInstitution; }

    public function getObservaciones(): ?string
    { return $this->observaciones; }

    public function setObservaciones(string $observaciones): self
    {
        $this->observaciones = $observaciones;

        return $this;
    }

    public function isLocked(): bool
    { return $this->locked; }

    public function setLocked(bool $locked): void
    { $this->locked = $locked; }

    public function hasHigherRolesThan(BaseUser $user): bool
    {
        return $this->hasRole('ROLE_SUPER_ADMIN')
            || ($this->hasRole('ROLE_ADMIN') && !$user->hasRole('ROLE_SUPER_ADMIN'));
    }

    public function __call($name, $arguments)
    {
        // TODO: Implement @method string getUserIdentifier()
    }

    public function getEnabled(): bool
    { return $this->enabled; }

    public function setEnabled(bool $isEnabled): self
    {
        $this->enabled = $isEnabled;

        return $this;
    }

    public function isEnabled(): bool
    { return $this->enabled; }
}
