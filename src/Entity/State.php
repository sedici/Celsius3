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
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;
use Celsius3\Repository\StateRepository;
use Doctrine\ORM\Mapping\Index;
use Celsius3\Entity\Instance;
use Celsius3\Entity\Request;
use Celsius3\Entity\BaseUser;
use Doctrine\Common\Collections\Collection;
use Gedmo\Timestampable\Traits\TimestampableEntity;


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


    #[ORM\OneToMany(targetEntity: Event::class, mappedBy: "state", cascade: ["persist"])]
    private ArrayCollection $events;


    #[ORM\OneToMany(targetEntity: MultiInstanceReceiveEvent::class, mappedBy: "remoteState")]
    private ArrayCollection $remoteEvents;


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


    #[Groups([
        "administration_list",
        "administration_order_show",
        "administration_user_show",
        "user_list"
    ])]
    protected \DateTime $createdAt;


    public function __construct()
    {
        $this->events = new ArrayCollection();
        $this->remoteEvents = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getType();
    }

    /**
     * Get id.
     */
    public function getId(): int|null
    {
        return $this->id;
    }

    /**
     * Set current.
     *
     * @param bool $current
     *
     * @return self
     */
    public function setCurrent($current)
    {
        $this->current = $current;

        return $this;
    }

    /**
     * Get current.
     *
     * @return bool $current
     */
    public function isCurrent()
    {
        return $this->current;
    }

    /**
     * Set searchPending.
     *
     * @param bool $searchPending
     *
     * @return self
     */
    public function setSearchPending($searchPending)
    {
        $this->searchPending = $searchPending;

        return $this;
    }

    /**
     * Get searchPending.
     *
     * @return bool $searchPending
     */
    public function getSearchPending()
    {
        return $this->searchPending;
    }

    /**
     * Set type.
     *
     * @param string $type
     *
     * @return self
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type.
     *
     * @return string $type
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set remoteEvent.
     *
     * @param $remoteEvent
     *
     * @return self
     */
    public function setRemoteEvent($remoteEvent)
    {
        $this->remoteEvent = $remoteEvent;

        return $this;
    }

    /**
     * Get remoteEvent.
     *
     * @return $remoteEvent
     */
    public function getRemoteEvent()
    {
        return $this->remoteEvent;
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
     * Add events.
     *
     * @param Event $events
     */
    public function addEvent(Event $events)
    {
        $this->events[] = $events;
    }

    /**
     * Remove events.
     *
     * @param Event $events
     */
    public function removeEvent(Event $events)
    {
        $this->events->removeElement($events);
    }

    /**
     * Get events.
     */
    public function getEvents(): array|Collection
    {
        return $this->events;
    }

    /**
     * Add remoteEvents.
     *
     * @param MultiInstanceReceiveEvent $remoteEvents
     */
    public function addRemoteEvent(MultiInstanceReceiveEvent $remoteEvents)
    {
        $this->remoteEvents[] = $remoteEvents;
    }

    /**
     * Remove remoteEvents.
     *
     * @param MultiInstanceReceiveEvent $remoteEvents
     */
    public function removeRemoteEvent(MultiInstanceReceiveEvent $remoteEvents)
    {
        $this->remoteEvents->removeElement($remoteEvents);
    }

    /**
     * Get remoteEvents.
     *
     * @return Collection $remoteEvents
     */
    public function getRemoteEvents()
    {
        return $this->remoteEvents;
    }

    /**
     * Set previous.
     */
    public function setPrevious(?State $previous = null): static
    {
        $this->previous = $previous;

        return $this;
    }

    /**
     * Get previous.
     */
    public function getPrevious(): State|null
    {
        return $this->previous;
    }

    /**
     * Set request.
     *
     * @param Request $request
     *
     * @return self
     */
    public function setRequest(Request $request): static
    {
        $this->request = $request;
        return $this;
    }

    /**
     * Get request.
     */
    public function getRequest(): Request
    {
        return $this->request;
    }

    /**
     * Set operator.
     */
    public function setOperator(BaseUser $operator): static
    {
        $this->operator = $operator;

        return $this;
    }

    /**
     * Get operator.
     */
    public function getOperator(): BaseUser|null
    {
        return $this->operator;
    }

    /**
     * Get current.
     */
    public function getCurrent(): bool
    {
        return $this->current;
    }
}
