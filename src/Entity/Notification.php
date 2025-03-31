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

use Celsius3\Entity\BaseUser;
use Celsius3\Entity\Event\Event;
use Celsius3\Entity\Mixin\TimestampableEntity;
use Celsius3\Entity\Template;
use Celsius3\Repository\NotificationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: NotificationRepository::class)]
#[ORM\Table(name: "notification", indexes: [
    new ORM\Index(name: "idx_viewed", columns: ["viewed"]),
    new ORM\Index(name: "idx_template", columns: ["template_id"]),
    new ORM\Index(name: "idx_object_user", columns: ["base_user_notification_id"]),
    new ORM\Index(name: "idx_object_message", columns: ["message_notification_id"]),
    new ORM\Index(name: "idx_object_event", columns: ["event_notification_id"])
])]
#[ORM\InheritanceType("SINGLE_TABLE")]
#[ORM\DiscriminatorColumn(name: "type", type: "string")]
#[ORM\DiscriminatorMap([
    "message" => MessageNotification::class,
    "baseuser" => BaseUserNotification::class,
    "event" => EventNotification::class,
])]
abstract class Notification
{
    use TimestampableEntity;

    #[ORM\Column(type: "integer")]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    private ?int $id = null;


    #[Assert\NotBlank]
    #[ORM\Column(type: "string", length: 255)]
    private ?string $cause = null;


    #[Assert\NotBlank]
    #[Assert\Type(type: "boolean")]
    #[ORM\Column(type: "boolean")]
    private bool $viewed = false;


    #[ORM\Column(type: 'datetime', nullable: true)]
    // #[Gedmo\Timestampable(on: "change", field: "viewed", value: true)]
    private ?\DateTime $viewedAt = null;


    #[ORM\ManyToOne(targetEntity: NotificationTemplate::class)]
    #[ORM\JoinColumn(
        name: "template_id", referencedColumnName: "id", nullable: false
    )]
    private ?Template $template = null;

    
    // --- campos inversos (no se guardan en esta tabla) ---


    #[ORM\ManyToMany(targetEntity: BaseUser::class)]
    #[ORM\JoinTable(name: "notification_receiver",
        joinColumns: [new ORM\JoinColumn(
            name: "notification_id",
            referencedColumnName: "id"
        )],
        inverseJoinColumns: [new ORM\JoinColumn(
            name: "receiver_id",
            referencedColumnName: "id"
        )]
    )]
    private Collection $receivers;


    #[ORM\ManyToMany(targetEntity: BaseUser::class)]
    #[ORM\JoinTable(name: "notification_viewer",
        joinColumns: [new ORM\JoinColumn(
            name: "notification_id",
            referencedColumnName: "id"
        )],
        inverseJoinColumns: [new ORM\JoinColumn(
            name: "receiver_id",
            referencedColumnName: "id",
            unique: true
        )]
    )]
    private Collection $viewers;


    abstract public function setObject(Event|Message|BaseUser $object): self;
    abstract public function getObject(): Event|Message|BaseUser;


    public function __construct()
    { $this->receivers = new ArrayCollection(); }

    public function getId(): ?int
    { return $this->id; }

    public function setCause(string $cause): self
    {
        $this->cause = $cause;

        return $this;
    }

    public function getCause(): ?string
    { return $this->cause; }

    public function setViewed(bool $viewed): self
    {
        $this->viewed = $viewed;

        return $this;
    }

    public function isViewed(): bool
    { return $this->viewed; }

    public function setViewedAt(?\DateTime $viewedAt): self
    {
        $this->viewedAt = $viewedAt;

        return $this;
    }

    public function getViewedAt(): ?\DateTime
    { return $this->viewedAt; }

    // public function setSource($source): self
    // {
    //     $this->source = $source;

    //     return $this;
    // }

    // public function getSource(): mixed
    // { return $this->source; }

    public function setTemplate(Template $template): self
    {
        $this->template = $template;

        return $this;
    }

    public function getTemplate(): ?Template
    { return $this->template; }

    public function addReceiver(BaseUser $receivers): void
    { $this->receivers[] = $receivers; }

    public function removeReceiver(BaseUser $receivers): void
    { $this->receivers->removeElement($receivers); }

    public function getReceivers(): array|ArrayCollection
    { return $this->receivers; }

    public function getViewed(): bool
    { return $this->viewed; }

    public function addViewer(BaseUser $viewer): self
    { $this->viewers->add($viewer); return $this; }

    public function removeViewer(BaseUser $viewer): void
    { $this->viewers->removeElement($viewer); }
}
