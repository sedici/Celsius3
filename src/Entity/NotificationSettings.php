<?php

namespace Celsius3\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: "notification_settings")]
#[ORM\Entity(repositoryClass: \Celsius3\Repository\BaseNotificationRepository::class)]
class NotificationSettings
{

    #[ORM\Column(name: "id", type: "integer")]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    private ?int $id = null;


    #[ORM\ManyToOne(targetEntity: BaseUser::class, inversedBy: "notificationSettings")]
    #[ORM\JoinColumn(name: "user", referencedColumnName: "id")]
    private ?BaseUser $user = null;


    #[ORM\ManyToOne(targetEntity: Instance::class)]
    #[ORM\JoinColumn(name: "instance", referencedColumnName: "id")]
    private ?Instance $instance = null;


    #[ORM\Column(name: "type", type: "string")]
    private ?string $type = null;


    #[ORM\Column(name: "subscribedToInterfaceNotifications", type: "boolean")]
    private ?bool $subscribedToInterfaceNotifications = null;


    #[ORM\Column(name: "subscribedToEmailNotifications", type: "boolean")]
    private ?bool $subscribedToEmailNotifications = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setSubscribedToInterfaceNotifications(bool $subscribedToInterfaceNotifications): self
    {
        $this->subscribedToInterfaceNotifications = $subscribedToInterfaceNotifications;

        return $this;
    }

    public function getSubscribedToInterfaceNotifications(): ?bool
    {
        return $this->subscribedToInterfaceNotifications;
    }

    public function setSubscribedToEmailNotifications(bool $subscribedToEmailNotifications): self
    {
        $this->subscribedToEmailNotifications = $subscribedToEmailNotifications;

        return $this;
    }

    public function getSubscribedToEmailNotifications(): ?bool
    {
        return $this->subscribedToEmailNotifications;
    }

    public function setUser(?BaseUser $user = null): self
    {
        $this->user = $user;

        return $this;
    }

    public function getUser(): ?BaseUser
    {
        return $this->user;
    }

    public function setInstance(?Instance $instance = null): self
    {
        $this->instance = $instance;

        return $this;
    }

    public function getInstance(): ?Instance
    {
        return $this->instance;
    }
}
