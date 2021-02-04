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

namespace Celsius3\CoreBundle\Entity\Event;

use Celsius3\CoreBundle\Entity\Instance;
use Celsius3\CoreBundle\Entity\Request;
use Celsius3\CoreBundle\Entity\State;
use Celsius3\CoreBundle\Helper\LifecycleHelper;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\UserInterface;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="Celsius3\CoreBundle\Repository\EventRepository")
 * @ORM\Table(name="event", indexes={
 *   @ORM\Index(name="idx_request", columns={"request_id"}),
 *   @ORM\Index(name="idx_operator", columns={"operator_id"}),
 *   @ORM\Index(name="idx_state", columns={"state_id"}),
 *   @ORM\Index(name="idx_instance", columns={"instance_id"}),
 *   @ORM\Index(name="idx_type", columns={"type"})
 * })
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap({
 *   "creation"="CreationEvent",
 *   "search"="SearchEvent",
 *   "sirequest"="SingleInstanceRequestEvent",
 *   "cancel"="CancelEvent",
 *   "annul"="AnnulEvent",
 *   "sireceive"="SingleInstanceReceiveEvent",
 *   "mireceive"="MultiInstanceReceiveEvent",
 *   "mirequest"="MultiInstanceRequestEvent",
 *   "deliver"="DeliverEvent",
 *   "localcancel"="LocalCancelEvent",
 *   "remotecancel"="RemoteCancelEvent",
 *   "reclaim"="ReclaimEvent",
 *   "approve"="ApproveEvent",
 *   "undo"="UndoEvent",
 *   "si"="SingleInstanceEvent",
 *   "mi"="MultiInstanceEvent",
 *   "take"="TakeEvent",
 *   "upload"="UploadEvent",
 *   "reupload"="ReuploadEvent",
 *   "searchpendings"="SearchPendingsEvent",
 *   "nosearchpendings"="NoSearchPendingsEvent"
 * })
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
abstract class Event implements EventInterface
{
    use TimestampableEntity;
    use SoftDeleteableEntity;

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $observations;

    /**
     * @Assert\NotNull
     * @ORM\ManyToOne(targetEntity="Celsius3\CoreBundle\Entity\Request", inversedBy="events")
     * @ORM\JoinColumn(name="request_id", referencedColumnName="id", nullable=false)
     */
    private $request;

    /**
     * @ORM\ManyToOne(targetEntity="Celsius3\CoreBundle\Entity\BaseUser")
     * @ORM\JoinColumn(name="operator_id", referencedColumnName="id")
     */
    private $operator;

    /**
     * @Assert\NotNull
     * @ORM\ManyToOne(
     *     targetEntity="Celsius3\CoreBundle\Entity\State",
     *     inversedBy="events",
     *     cascade={"persist", "refresh"}
     * )
     * @ORM\JoinColumn(name="state_id", referencedColumnName="id", nullable=false)
     */
    private $state;

    /**
     * @Assert\NotNull
     * @ORM\ManyToOne(targetEntity="Celsius3\CoreBundle\Entity\Instance", inversedBy="events")
     * @ORM\JoinColumn(name="instance_id", referencedColumnName="id", nullable=false)
     */
    private $instance;

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

    public function getOperator(): ?UserInterface
    {
        return $this->operator;
    }

    public function setOperator(UserInterface $operator = null): Event
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
