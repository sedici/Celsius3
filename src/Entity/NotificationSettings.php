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
