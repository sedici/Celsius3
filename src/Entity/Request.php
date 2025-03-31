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
use Celsius3\Entity\Mixin\SoftDeleteableEntity;
use Celsius3\Entity\Mixin\TimestampableEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;


#[ORM\Entity(repositoryClass: 'Celsius3\Repository\RequestRepository')]
#[ORM\Table(name: 'request', indexes: [
    new ORM\Index(name: 'idx_type', columns: ['type']),
    new ORM\Index(name: 'idx_owner', columns: ['owner_id']),
    new ORM\Index(name: 'idx_creator', columns: ['creator_id']),
    new ORM\Index(name: 'idx_librarian', columns: ['librarian_id']),
    new ORM\Index(name: 'idx_instance', columns: ['instance_id']),
    new ORM\Index(name: 'idx_operator', columns: ['operator_id']),
    new ORM\Index(name: 'idx_order', columns: ['order_id']),
    new ORM\Index(name: 'idx_previous_request', columns: ['previous_request_id']),
], uniqueConstraints: [
    new ORM\UniqueConstraint(name: 'idx_order_instance', columns: ['instance_id', 'order_id']),
])]
#[Gedmo\SoftDeleteable(fieldName: 'deletedAt', timeAware: false)]
class Request
{
    use TimestampableEntity;
    use SoftDeleteableEntity;

    #[ORM\Column(type: 'integer')]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[Groups([
        'api',
        'administration_list',
        'administration_order_show',
        'administration_user_show',
        'user_list'
    ])]
    private int $id;


    #[Assert\NotBlank]
    #[Assert\Choice(
        callback: [
            '\Celsius3\Manager\OrderManager',
            'getTypes'
        ],
        message: 'Choose a valid type.'
    )]
    #[ORM\Column(type: 'string', length: 255)]
    #[Groups([
        'api',
        'administration_list',
        'administration_order_show',
        'administration_user_show',
        'user_list'
    ])]
    private string $type;


    #[ORM\Column(type: 'text', nullable: true)]
    #[Groups([
        'api',
        'administration_list',
        'administration_order_show',
        'administration_user_show'
    ])]
    private ?string $comments = null;


    #[ORM\ManyToOne(targetEntity: BaseUser::class, inversedBy: 'orders')]
    #[ORM\JoinColumn(name: 'owner_id', referencedColumnName: 'id', nullable: false)]
    #[Groups([
        'api',
        'administration_list',
        'administration_order_show',
        'administration_user_show',
        'user_list'
    ])]
    private BaseUser $owner;


    #[ORM\ManyToOne(targetEntity: BaseUser::class, inversedBy: 'createdOrders')]
    #[ORM\JoinColumn(name: 'creator_id', referencedColumnName: 'id', nullable: false)]
    private BaseUser $creator;


    #[ORM\ManyToOne(targetEntity: BaseUser::class)]
    #[ORM\JoinColumn(name: 'librarian_id', referencedColumnName: 'id')]
    private ?BaseUser $librarian = null;


    #[ORM\OneToMany(targetEntity: File::class, mappedBy: 'request')]
    #[Groups([
        'administration_order_show',
        'user_list'
    ])]
    private ArrayCollection $files;


    #[ORM\OneToMany(targetEntity: Event::class, mappedBy: 'request', fetch: 'EAGER')]
    #[Groups([
        'administration_list',
        'administration_order_show',
        'administration_user_show'
    ])]
    private ArrayCollection $events;


    #[ORM\OneToMany(targetEntity: State::class, mappedBy: 'request', fetch: 'EAGER')]
    #[Groups([
        'administration_list',
        'administration_order_show',
        'administration_user_show'
    ])]
    private ArrayCollection $states;


    #[Assert\NotNull(groups: ['Default', 'newOrder'])]
    #[ORM\ManyToOne(targetEntity: Instance::class, inversedBy: 'orders')]
    #[ORM\JoinColumn(name: 'instance_id', referencedColumnName: 'id', nullable: false)]
    #[Groups([
        'administration_order_show',
        'administration_user_show'
    ])]
    private Instance $instance;


    #[ORM\ManyToOne(targetEntity: BaseUser::class, inversedBy: 'operatedOrders')]
    #[ORM\JoinColumn(name: 'operator_id', referencedColumnName: 'id')]
    #[Groups([
        'administration_list',
        'administration_order_show',
        'administration_user_show',
        'user_list'
    ])]
    private ?BaseUser $operator = null;


    #[ORM\ManyToOne(targetEntity: Order::class, inversedBy: 'requests', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(name: 'order_id', referencedColumnName: 'id', nullable: false)]
    #[Groups(['administration_order_show'])]
    private Order $order;


    #[ORM\ManyToOne(targetEntity: Request::class, inversedBy: 'requests')]
    #[ORM\JoinColumn(name: 'previous_request_id', referencedColumnName: 'id')]
    #[Groups(['administration_order_show'])]
    private ?Request $previousRequest = null;


    #[ORM\OneToMany(targetEntity: Request::class, mappedBy: 'previousRequest')]
    private ArrayCollection $requests;


    #[Groups([
        'api',
        'administration_list',
        'administration_order_show',
        'administration_user_show',
        'user_list'
    ])]
    protected \DateTime $createdAt;


    public function __construct()
    {
        $this->files = new ArrayCollection();
        $this->events = new ArrayCollection();
        $this->states = new ArrayCollection();
        $this->requests = new ArrayCollection();
    }

    /**
     * Get id.
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Set type.
     *
     * @param string $type
     *
     * @return self
     */
    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type.
     *
     * @return string $type
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Set comments.
     *
     * @param string $comments
     *
     * @return self
     */
    public function setComments(?string $comments): self
    {
        $this->comments = $comments;

        return $this;
    }

    /**
     * Get comments.
     *
     * @return string $comments
     */
    public function getComments(): ?string
    {
        return $this->comments;
    }

    /**
     * Set owner.
     *
     * @param BaseUser $owner
     *
     * @return self
     */
    public function setOwner(BaseUser $owner): static
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * Get owner.
     *
     * @return BaseUser $owner
     */
    public function getOwner(): BaseUser
    {
        return $this->owner;
    }

    /**
     * Set creator.
     *
     * @param BaseUser $creator
     *
     * @return self
     */
    public function setCreator(BaseUser $creator): self
    {
        $this->creator = $creator;

        return $this;
    }

    /**
     * Get creator.
     *
     * @return BaseUser $creator
     */
    public function getCreator(): BaseUser
    {
        return $this->creator;
    }

    /**
     * Set librarian.
     *
     * @param BaseUser $librarian
     *
     * @return self
     */
    public function setLibrarian(?BaseUser $librarian): self
    {
        $this->librarian = $librarian;

        return $this;
    }

    /**
     * Get librarian.
     *
     * @return BaseUser $librarian
     */
    public function getLibrarian(): ?BaseUser
    {
        return $this->librarian;
    }

    /**
     * Add file.
     *
     * @param File $file
     */
    public function addFile(File $file): void
    {
        $this->files[] = $file;
    }

    /**
     * Remove file.
     *
     * @param File $file
     */
    public function removeFile(File $file): void
    {
        $this->files->removeElement($file);
    }

    /**
     * Get files.
     */
    public function getFiles(): array|Collection
    {
        return $this->files;
    }

    /**
     * Add event.
     *
     * @param Event $event
     */
    public function addEvent(Event $event): void
    {
        $this->events[] = $event;
    }

    /**
     * Remove event.
     *
     * @param Event $event
     */
    public function removeEvent(Event $event): void
    {
        $this->events->removeElement($event);
    }

    /**
     * Get events.
     *
     * @return Collection $events
     */
    public function getEvents(): Collection
    {
        return $this->events;
    }

    /**
     * Add state.
     *
     * @param State $state
     */
    public function addState(State $state): void
    {
        $this->states[] = $state;
    }

    /**
     * Remove state.
     *
     * @param State $state
     */
    public function removeState(State $state): void
    {
        $this->states->removeElement($state);
    }

    /**
     * Get states.
     *
     * @return Collection $states
     */
    public function getStates(): Collection
    {
        return $this->states;
    }

    /**
     * Set instance.
     *
     * @param Instance $instance
     *
     * @return self
     */
    public function setInstance(Instance $instance): self
    {
        $this->instance = $instance;

        return $this;
    }

    /**
     * Get instance.
     *
     * @return Instance $instance
     */
    public function getInstance(): Instance
    {
        return $this->instance;
    }

    /**
     * Set operator.
     *
     * @param BaseUser $operator
     *
     * @return self
     */
    public function setOperator(?BaseUser $operator): self
    {
        $this->operator = $operator;

        return $this;
    }

    /**
     * Get operator.
     *
     * @return BaseUser $operator
     */
    public function getOperator(): ?BaseUser
    {
        return $this->operator;
    }

    /**
     * Set order.
     *
     * @param Order $order
     *
     * @return self
     */
    public function setOrder(Order $order): self
    {
        $this->order = $order;

        return $this;
    }

    /**
     * Get order.
     *
     * @return Order $order
     */
    public function getOrder(): Order
    {
        return $this->order;
    }

    /**
     * Se buscan los archivos para un determinado evento.
     */
    public function getFilesForEvent($event): Collection
    {
        return $this->getFiles()->filter(
            function (File $entry) use ($event): bool {
                return $entry->getEvent()->getId() == $event->getId();
            }
        );
    }

    /**
     * Se buscan los archivos para descargar por el usuario.
     */
    public function getFilesForDownload(): Collection
    {
        $instance = $this->getOrder()->getOriginalRequest()->getInstance();

        return $this->getFiles()->filter(
            function (File $entry) use ($instance): bool {
                return $entry->getEvent()->getInstance()->getId() == $instance->getId();
            }
        );
    }

    /**
     * Se buscan los archivos que aún no han sido descargados.
     */
    public function getNotDownloadedFiles(): Collection
    {
        $instance = $this->getOrder()->getOriginalRequest()->getInstance();

        return $this->getFiles()->filter(
            function (File $entry) use ($instance): bool {
                return $entry->getEvent()->getInstance()->getId() == (
                    $instance->getId() && !$entry->isDownloaded()
                );
            }
        );
    }

    /**
     * Retorna si el Request actual ha alcanzado un determinado estado o estados.
     */
    public function hasState($names): bool
    {
        if (!is_array($names)) {
            $names = array($names);
        }

        return $this->getStates()->filter(
            function (State $entry) use ($names): bool {
                return in_array(
                    $entry->getType(),
                    $names
                );
            }
        )->count() > 0;
    }

    /**
     * Retorna el estado con nombre $name para el Request actual.
     * Antes debería verificarse su existencia con hasState.
     */
    public function getState(string $name): ?State
    {
        $result = $this->getStates()->filter(
            function (State $entry) use ($name): bool {
                return $entry->getType() === $name;
            }
        )->first();

        return false !== $result ? $result : null;
    }

    /**
     * Retorna el estado actual para el presente Request.
     */
    #[Groups([
        'api',
        'administration_list',
        'administration_order_show',
        'administration_user_show',
        'user_list'
    ])]
    public function getCurrentState(): ?State
    {
        $result = $this->getStates()->filter(
            fn (State $entry): bool =>
                $entry->isCurrent()
        )->first();

        return false !== $result ? $result : null;
    }

    /**
     * Set previousRequest.
     *
     * @param Request $previousRequest
     *
     * @return self
     */
    public function setPreviousRequest(?Request $previousRequest): self
    {
        $this->previousRequest = $previousRequest;

        return $this;
    }

    /**
     * Get previousRequest.
     *
     * @return Request $previousRequest
     */
    public function getPreviousRequest(): ?Request
    {
        return $this->previousRequest;
    }

    /**
     * Add request.
     *
     * @param Request $request
     */
    public function addRequest(Request $request): void
    {
        $this->requests[] = $request;
    }

    /**
     * Remove request.
     *
     * @param Request $request
     */
    public function removeRequest(Request $request): void
    {
        $this->requests->removeElement($request);
    }

    /**
     * Get requests.
     *
     * @return Collection $requests
     */
    public function getRequests(): Collection
    {
        return $this->requests;
    }

    #[Groups([
        'api',
        'administration_list',
        'administration_order_show',
        'administration_user_show',
        'user_list'
        ])]
    public function hasDownloadableFiles(): bool
    {
        $files = $this->getFiles()->filter(
            fn (File $f): bool =>
                !$f->isDownloaded() || $f->hasDownloadTime()
        );

        return $files->count() > 0;
    }
}
