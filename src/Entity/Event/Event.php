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

declare(strict_types=1);

namespace Celsius3\Entity\Event;

use Celsius3\Entity\BaseUser;
use Celsius3\Entity\Instance;
use Celsius3\Entity\Request;
use Celsius3\Entity\State;
use Celsius3\Helper\LifecycleHelper;
use Celsius3\Manager\EventManager;
use Celsius3\Repository\EventRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Serializer\Annotation\Groups;
// use Celsius3\Entity\Mixin\SoftDeleteableEntity;
// use Celsius3\Entity\Mixin\TimestampableEntity;
use Symfony\Component\Validator\Constraints as Assert;

// #[ORM\DiscriminatorMap([
//     'creation' => CreationEvent::class,
//     'search' => SearchEvent::class,
//     'sirequest' => SingleInstanceRequestEvent::class,
//     'cancel' => CancelEvent::class,
//     'annul' => AnnulEvent::class,
//     'sireceive' => SingleInstanceReceiveEvent::class,
//     'mireceive' => MultiInstanceReceiveEvent::class,
//     'mirequest' => MultiInstanceRequestEvent::class,
//     'deliver' => DeliverEvent::class,
//     'localcancel' => LocalCancelEvent::class,
//     'remotecancel' => RemoteCancelEvent::class,
//     'reclaim' => ReclaimEvent::class,
//     'approve' => ApproveEvent::class,
//     'undo' => UndoEvent::class,
//     'si' => SingleInstanceEvent::class,
//     'mi' => MultiInstanceEvent::class,
//     'take' => TakeEvent::class,
//     'upload' => UploadEvent::class,
//     'reupload' => ReuploadEvent::class,
//     'searchpendings' => SearchPendingsEvent::class,
//     'nosearchpendings' => NoSearchPendingsEvent::class,
// ])]

#[
    ORM\Table(name: 'event'),
    ORM\Entity(repositoryClass: EventRepository::class),

    ORM\Index(name: 'idx_request', columns: ['request_id']),
    ORM\Index(name: 'idx_operator', columns: ['operator_id']),
    ORM\Index(name: 'idx_state', columns: ['state_id']),
    ORM\Index(name: 'idx_instance', columns: ['instance_id']),
    ORM\Index(name: 'idx_type', columns: ['type']),

    ORM\InheritanceType('SINGLE_TABLE'),
    ORM\DiscriminatorColumn(name: 'type', type: 'string'),

    ORM\DiscriminatorMap([
        EventManager::EVENT__CREATION                       => CreationEvent::class,
        EventManager::EVENT__SEARCH                         => SearchEvent::class,
        EventManager::EVENT__SINGLE_INSTANCE_REQUEST        => SingleInstanceRequestEvent::class,
        EventManager::EVENT__CANCEL                         => CancelEvent::class,
        EventManager::EVENT__ANNUL                          => AnnulEvent::class,
        EventManager::EVENT__SINGLE_INSTANCE_RECEIVE        => SingleInstanceReceiveEvent::class,
        EventManager::EVENT__MULTI_INSTANCE_RECEIVE         => MultiInstanceReceiveEvent::class,
        EventManager::EVENT__MULTI_INSTANCE_REQUEST         => MultiInstanceRequestEvent::class,
        EventManager::EVENT__DELIVER                        => DeliverEvent::class,
        EventManager::EVENT__LOCAL_CANCEL                   => LocalCancelEvent::class,
        EventManager::EVENT__REMOTE_CANCEL                  => RemoteCancelEvent::class,
        EventManager::EVENT__RECLAIM                        => ReclaimEvent::class,
        EventManager::EVENT__APPROVE                        => ApproveEvent::class,
        EventManager::EVENT__TAKE                           => TakeEvent::class,
        EventManager::EVENT__UPLOAD                         => UploadEvent::class,
        EventManager::EVENT__REUPLOAD                       => ReuploadEvent::class,
        EventManager::EVENT__SEARCH_PENDINGS                => SearchPendingsEvent::class,
        EventManager::EVENT__NO_SEARCH_PENDINGS             => NoSearchPendingsEvent::class,
        'undo'                                              => UndoEvent::class,
        'si'                                                => SingleInstanceEvent::class,
        'mi'                                                => MultiInstanceEvent::class,
    ]),

    Gedmo\SoftDeleteable(fieldName: 'deletedAt', timeAware: false)
]
abstract class Event implements EventInterface, \Stringable
{
    use TimestampableEntity;
    use SoftDeleteableEntity;

    #[ORM\Column(type: 'integer')]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    private $id;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $observations;

    #[Assert\NotNull]
    #[ORM\ManyToOne(targetEntity: Request::class, inversedBy: 'events')]
    #[ORM\JoinColumn(name: 'request_id', referencedColumnName: 'id', nullable: false)]
    private Request $request;


    #[ORM\ManyToOne(targetEntity: BaseUser::class, inversedBy: 'events')]
    #[ORM\JoinColumn(name: 'operator_id', referencedColumnName: 'id')]
    private ?BaseUser $operator;


    #[Assert\NotNull]
    #[ORM\ManyToOne(targetEntity: State::class, inversedBy: 'events', cascade: ['persist', 'refresh'])]
    #[ORM\JoinColumn(name: 'state_id', referencedColumnName: 'id', nullable: false)]
    private State $state;


    #[Assert\NotNull]
    #[ORM\ManyToOne(targetEntity: Instance::class, inversedBy: 'events')]
    #[ORM\JoinColumn(name: 'instance_id', referencedColumnName: 'id', nullable: false)]
    private Instance $instance;


    abstract public function getEventType(): string;


    public function __toString()
    {
        $title = $this->getRequest()->getOrder()->getMaterialData()->getTitle();
        $code = $this->getRequest()->getOrder()->getCode();

        return "($code) $title";
    }

    public function getRequest(): Request
    {
        return $this->request;
    }

    public function setRequest(Request $request): self
    {
        $this->request = $request;

        return $this;
    }

    public function applyExtraData(Request $request, array $data, LifecycleHelper $lifecycleHelper, $date): void
    {
    }

    public function getId()
    {
        return $this->id;
    }

    public function getObservations(): ?string
    {
        return $this->observations;
    }

    public function setObservations(?string $observations): self
    {
        $this->observations = $observations;

        return $this;
    }

    public function getOperator(): ?BaseUser
    { return $this->operator; }

    public function setOperator(?BaseUser $operator = null): Event
    {
        $this->operator = $operator;
        return $this;
    }

    public function getState(): State
    {
        return $this->state;
    }

    public function setState(State $state): self
    {
        $this->state = $state;

        return $this;
    }

    public function getInstance(): Instance
    {
        return $this->instance;
    }
    
    public function setInstance(Instance $instance): self
    {
        $this->instance = $instance;

        return $this;
    }
}
