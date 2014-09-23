<?php
/*
 * Celsius3 - Order management
 * Copyright (C) 2014 PrEBi <info@prebi.unlp.edu.ar>
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

namespace Celsius3\CoreBundle\Document\Event;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Celsius3\CoreBundle\Helper\LifecycleHelper;
use Celsius3\CoreBundle\Document\Request;
use Gedmo\Timestampable\Traits\TimestampableDocument;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ODM\Document
 * @ODM\Document(repositoryClass="Celsius3\CoreBundle\Repository\EventRepository")
 * @ODM\InheritanceType("SINGLE_COLLECTION")
 * @ODM\DiscriminatorField(fieldName="type")
 * @ODM\DiscriminatorMap({
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
 *   "reupload"="ReuploadEvent"
 * })
 */
abstract class Event implements EventInterface
{
    use TimestampableDocument;
    /**
     * @ODM\Id
     */
    private $id;
    /**
     * @Assert\Date()
     * @ODM\Date
     */
    private $date;
    /**
     * @ODM\String
     */
    private $observations;
    /**
     * @Assert\NotNull
     * @ODM\ReferenceOne(targetDocument="Celsius3\CoreBundle\Document\Request", inversedBy="events")
     */
    private $request;
    /**
     * @ODM\ReferenceOne(targetDocument="Celsius3\CoreBundle\Document\BaseUser", inversedBy="events")
     */
    private $operator;
    /**
     * @Assert\NotNull
     * @ODM\ReferenceOne(targetDocument="Celsius3\CoreBundle\Document\State", inversedBy="events", cascade={"persist", "refresh"})
     */
    private $state;
    /**
     * @Assert\NotNull
     * @ODM\ReferenceOne(targetDocument="Celsius3\CoreBundle\Document\Instance", inversedBy="events")
     */
    private $instance;

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
     * Set date
     *
     * @param  date $date
     * @return self
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return date $date
     */
    public function getDate()
    {
        return $this->date;
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
     * @param  Celsius3\CoreBundle\Document\BaseUser $operator
     * @return self
     */
    public function setOperator(\Celsius3\CoreBundle\Document\BaseUser $operator = null)
    {
        $this->operator = $operator;

        return $this;
    }

    /**
     * Get operator
     *
     * @return Celsius3\CoreBundle\Document\BaseUser $operator
     */
    public function getOperator()
    {
        return $this->operator;
    }

    /**
     * Set state
     *
     * @param  Celsius3\CoreBundle\Document\State $state
     * @return self
     */
    public function setState(\Celsius3\CoreBundle\Document\State $state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return Celsius3\CoreBundle\Document\State $state
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set instance
     *
     * @param  Celsius3\CoreBundle\Document\Instance $instance
     * @return self
     */
    public function setInstance(\Celsius3\CoreBundle\Document\Instance $instance)
    {
        $this->instance = $instance;

        return $this;
    }

    /**
     * Get instance
     *
     * @return Celsius3\CoreBundle\Document\Instance $instance
     */
    public function getInstance()
    {
        return $this->instance;
    }

    /**
     * Set request
     *
     * @param  Celsius3\CoreBundle\Document\Request $request
     * @return self
     */
    public function setRequest(\Celsius3\CoreBundle\Document\Request $request)
    {
        $this->request = $request;

        return $this;
    }

    /**
     * Get request
     *
     * @return Celsius3\CoreBundle\Document\Request $request
     */
    public function getRequest()
    {
        return $this->request;
    }
}