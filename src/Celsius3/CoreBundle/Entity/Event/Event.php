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

namespace Celsius3\CoreBundle\Entity\Event;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Celsius3\CoreBundle\Helper\LifecycleHelper;
use Celsius3\CoreBundle\Entity\Request;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;

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
     * @ORM\ManyToOne(targetEntity="Celsius3\CoreBundle\Entity\State", inversedBy="events", cascade={"persist", "refresh"})
     * @ORM\JoinColumn(name="state_id", referencedColumnName="id", nullable=false)
     */
    private $state;

    /**
     * @Assert\NotNull
     * @ORM\ManyToOne(targetEntity="Celsius3\CoreBundle\Entity\Instance", inversedBy="events")
     * @ORM\JoinColumn(name="instance_id", referencedColumnName="id", nullable=false)
     */
    private $instance;

    abstract public function getEventType();

    public function __toString()
    {
        $title = $this->getRequest()->getOrder()->getMaterialData()->getTitle();
        $code = $this->getRequest()->getOrder()->getCode();

        return  "($code) $title";
    }

    public function applyExtraData(Request $request, array $data, LifecycleHelper $lifecycleHelper, $date)
    {

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
     * Set observations
     *
     * @param  string $observations
     * @return self
     */
    public function setObservations($observations)
    {
        $this->observations = $observations;

        return $this;
    }

    /**
     * Get observations
     *
     * @return string $observations
     */
    public function getObservations()
    {
        return $this->observations;
    }

    /**
     * Set operator
     *
     * @param  Celsius3\CoreBundle\Entity\BaseUser $operator
     * @return self
     */
    public function setOperator(\Celsius3\CoreBundle\Entity\BaseUser $operator = null)
    {
        $this->operator = $operator;

        return $this;
    }

    /**
     * Get operator
     *
     * @return Celsius3\CoreBundle\Entity\BaseUser $operator
     */
    public function getOperator()
    {
        return $this->operator;
    }

    /**
     * Set state
     *
     * @param  Celsius3\CoreBundle\Entity\State $state
     * @return self
     */
    public function setState(\Celsius3\CoreBundle\Entity\State $state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return Celsius3\CoreBundle\Entity\State $state
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set instance
     *
     * @param  Celsius3\CoreBundle\Entity\Instance $instance
     * @return self
     */
    public function setInstance(\Celsius3\CoreBundle\Entity\Instance $instance)
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
     * Set request
     *
     * @param  Celsius3\CoreBundle\Entity\Request $request
     * @return self
     */
    public function setRequest(\Celsius3\CoreBundle\Entity\Request $request)
    {
        $this->request = $request;

        return $this;
    }

    /**
     * Get request
     *
     * @return Celsius3\CoreBundle\Entity\Request $request
     */
    public function getRequest()
    {
        return $this->request;
    }

}
