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

use Celsius3\Entity\Event\Event;
use Celsius3\Entity\Event\MultiInstanceReceiveEvent;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;
use Celsius3\Repository\StateRepository;
use Doctrine\ORM\Mapping\Index;
use Celsius3\Entity\Instance;
use Celsius3\Entity\Request;
use Celsius3\Entity\BaseUser;
use Celsius3\Entity\Mixin\SoftDeleteableEntity;
use Celsius3\Entity\Mixin\TimestampableEntity;
use Doctrine\Common\Collections\Collection;


#[ORM\Entity(repositoryClass: StateRepository::class)]
#[ORM\Table(name: "state", indexes: [
    new Index(name: "idx_current", columns: ["current"]),
    new Index(name: "idx_type", columns: ["type"]),
    new Index(name: "idx_previous", columns: ["previous_id"]),
    new Index(name: "idx_request", columns: ["request_id"]),
    new Index(name: "idx_instance", columns: ["instance_id"]),
    new Index(name: "idx_operator", columns: ["operator_id"])
])]
#[Gedmo\SoftDeleteable(fieldName: "deletedAt", timeAware: false)]
class State
{
    use TimestampableEntity;
    use SoftDeleteableEntity;

    #[ORM\Column(type: "integer")]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    #[Groups([
        "administration_list",
        "administration_order_show",
        "administration_user_show",
        "user_list"
    ])]
    private ?int $id = null;


    #[Assert\NotBlank]
    #[Assert\Type(type: "boolean")]
    #[ORM\Column(type: "boolean")]
    #[Groups([
        "administration_list",
        "administration_order_show",
        "administration_user_show",
        "user_list"
    ])]
    private bool $current = true;


    #[Assert\NotBlank]
    #[Assert\Type(type: "boolean")]
    #[ORM\Column(type: "boolean")]
    #[Groups(["administration_order_show"])]
    private bool $searchPending = false;


    #[Assert\NotBlank]
    #[ORM\Column(type: "string", length: 255)]
    #[Groups([
        "administration_list",
        "administration_order_show",
        "administration_user_show",
        "user_list"
    ])]
    private string $type;


    #[ORM\OneToOne(targetEntity: Event::class, cascade: ["persist"])]
    #[ORM\JoinColumn(name: "remote_event_id", referencedColumnName: "id")]
    #[Groups(["administration_order_show"])]
    private ?Event $remoteEvent = null;


    #[Assert\NotNull]
    #[ORM\ManyToOne(targetEntity: Instance::class, inversedBy: "states")]
    #[ORM\JoinColumn(name: "instance_id", referencedColumnName: "id", nullable: false)]
    #[Groups(["administration_order_show"])]
    private Instance $instance;


    #[ORM\OneToMany(targetEntity: Event::class, mappedBy: "state", cascade: ["persist"], fetch: "EXTRA_LAZY")]
    private Collection $events;


    #[ORM\OneToMany(targetEntity: MultiInstanceReceiveEvent::class, mappedBy: "remoteState", fetch: "EXTRA_LAZY")]
    private Collection $remoteEvents;


    #[ORM\ManyToOne(targetEntity: State::class)]
    #[ORM\JoinColumn(name: "previous_id", referencedColumnName: "id")]
    private ?State $previous = null;


    #[Assert\NotNull]
    #[ORM\ManyToOne(targetEntity: Request::class, inversedBy: "states")]
    #[ORM\JoinColumn(name: "request_id", referencedColumnName: "id", nullable: false)]
    private Request $request;


    #[ORM\ManyToOne(targetEntity: BaseUser::class)]
    #[ORM\JoinColumn(name: "operator_id", referencedColumnName: "id")]
    private ?BaseUser $operator = null;


    // #[Groups([
    //     "administration_list",
    //     "administration_order_show",
    //     "administration_user_show",
    //     "user_list"
    // ])]
    // protected \DateTime $createdAt;


    public function __construct()
    {
        $this->events = new ArrayCollection();
        $this->remoteEvents = new ArrayCollection();
    }

    public function __toString(): string
    { return $this->getType(); }


    public function getId(): ?int
    { return $this->id; }


    public function setCurrent($current): static
    {
        $this->current = $current;

        return $this;
    }


    public function isCurrent(): bool
    { return $this->current; }


    public function setSearchPending($searchPending): static
    {
        $this->searchPending = $searchPending;

        return $this;
    }


    public function getSearchPending(): bool
    { return $this->searchPending; }


    public function setType($type): static
    {
        $this->type = $type;

        return $this;
    }


    public function getType(): string
    { return $this->type; }


    public function setRemoteEvent($remoteEvent): static
    {
        $this->remoteEvent = $remoteEvent;

        return $this;
    }


    public function getRemoteEvent(): ?Event
    { return $this->remoteEvent; }


    public function setInstance(Instance $instance): static
    {
        $this->instance = $instance;

        return $this;
    }


    public function getInstance(): Instance
    { return $this->instance; }


    public function addEvent(Event $events): void
    { $this->events[] = $events; }


    public function removeEvent(Event $events): void
    { $this->events->removeElement($events); }


    public function getEvents(): array|Collection
    { return $this->events; }


    public function addRemoteEvent(
        MultiInstanceReceiveEvent $remoteEvents
    ): void {
        $this->remoteEvents[] = $remoteEvents;
    }


    public function removeRemoteEvent(
        MultiInstanceReceiveEvent $remoteEvents
    ): void {
        $this->remoteEvents->removeElement($remoteEvents);
    }


    public function getRemoteEvents(): array|Collection
    { return $this->remoteEvents; }


    public function setPrevious(?State $previous = null): static
    {
        $this->previous = $previous;

        return $this;
    }


    public function getPrevious(): ?State
    { return $this->previous; }


    public function setRequest(Request $request): static
    {
        $this->request = $request;
        return $this;
    }


    public function getRequest(): Request
    { return $this->request; }


    public function setOperator(BaseUser $operator): static
    {
        $this->operator = $operator;

        return $this;
    }


    public function getOperator(): BaseUser|null
    { return $this->operator; }


    public function getCurrent(): bool
    { return $this->current; }
}
