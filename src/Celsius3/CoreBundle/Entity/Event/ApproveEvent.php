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

namespace Celsius3\CoreBundle\Entity\Event;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Celsius3\CoreBundle\Helper\LifecycleHelper;
use Celsius3\CoreBundle\Entity\Request;
use Celsius3\CoreBundle\Manager\EventManager;
use Celsius3\CoreBundle\Manager\StateManager;
use Celsius3\CoreBundle\Entity\Mixin\ReclaimableTrait;
use Celsius3\CoreBundle\Entity\Mixin\ApprovableTrait;

/**
 * @ORM\Entity
 */
class ApproveEvent extends MultiInstanceEvent
{

    use ReclaimableTrait,
        ApprovableTrait;

    public function getEventType()
    {
        return 'approve';
    }

    /**
     * @ORM\ManyToMany(targetEntity="Celsius3\CoreBundle\Entity\File", cascade={"persist"})
     * @ORM\JoinTable(name="approves_files",
     *      joinColumns={@ORM\JoinColumn(name="event_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="file_id", referencedColumnName="id", unique=true)}
     *      )
     */
    private $files;

    /**
     * @Assert\NotNull
     * @ORM\ManyToOne(targetEntity="Celsius3\CoreBundle\Entity\State", inversedBy="remoteEvents", cascade={"persist",  "refresh"})
     * @ORM\JoinColumn(name="remote_state_id", referencedColumnName="id")
     */
    private $remoteState;

    /**
     * @Assert\NotNull
     * @ORM\OneToOne(targetEntity="Celsius3\CoreBundle\Entity\Event\Event")
     * @ORM\JoinColumn(name="receive_event_id", referencedColumnName="id")
     */
    private $receiveEvent;
    
    /**
     * @Assert\NotNull
     * @ORM\ManyToOne(targetEntity="Celsius3\CoreBundle\Entity\Event\Event")
     * @ORM\JoinColumn(name="request_event_id", referencedColumnName="id")
     */
    private $requestEvent;

    public function applyExtraData(Request $request, array $data, LifecycleHelper $lifecycleHelper, $date)
    {
        $this->setReceiveEvent($data['extraData']['receive']);
        $this->setRequestEvent($data['extraData']['request']);
        $this->getReceiveEvent()->setIsApproved(true);
        $lifecycleHelper->refresh($this->getReceiveEvent());
        $lifecycleHelper->copyFilesToPreviousRequest($request, $data['extraData']['receive']->getRequest(), $this);
        $lifecycleHelper->createEvent(EventManager::EVENT__DELIVER, $data['extraData']['receive']->getRequest(), $data['extraData']['receive']->getRequest()->getInstance());

        if (!is_null($request->getPreviousRequest())) {
            $this->setRemoteInstance($request->getPreviousRequest()->getInstance());
            $data['instance'] = $this->getRemoteInstance();
            $data['stateName'] = StateManager::STATE__APPROVAL_PENDING;
            $this->setRemoteState($lifecycleHelper->getState($request->getPreviousRequest(), $data, $this));
        }
    }

    /**
     * Set receiveEvent
     *
     * @param  Celsius3\CoreBundle\Entity\Event\Event $receiveEvent
     * @return self
     */
    public function setReceiveEvent(\Celsius3\CoreBundle\Entity\Event\Event $receiveEvent)
    {
        $this->receiveEvent = $receiveEvent;

        return $this;
    }

    /**
     * Get receiveEvent
     *
     * @return Celsius3\CoreBundle\Entity\Event\Event $receiveEvent
     */
    public function getReceiveEvent()
    {
        return $this->receiveEvent;
    }

    /**
     * Add files
     *
     * @param Celsius3\CoreBundle\Entity\File $files
     */
    public function addFile(\Celsius3\CoreBundle\Entity\File $files)
    {
        $this->files[] = $files;
    }

    /**
     * Remove files
     *
     * @param Celsius3\CoreBundle\Entity\File $files
     */
    public function removeFile(\Celsius3\CoreBundle\Entity\File $files)
    {
        $this->files->removeElement($files);
    }

    /**
     * Get files
     *
     * @return Doctrine\Common\Collections\Collection $files
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->files = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set remoteState
     *
     * @param \Celsius3\CoreBundle\Entity\State $remoteState
     *
     * @return ApproveEvent
     */
    public function setRemoteState(\Celsius3\CoreBundle\Entity\State $remoteState = null)
    {
        $this->remoteState = $remoteState;

        return $this;
    }

    /**
     * Get remoteState
     *
     * @return \Celsius3\CoreBundle\Entity\State
     */
    public function getRemoteState()
    {
        return $this->remoteState;
    }


    /**
     * Set requestEvent
     *
     * @param \Celsius3\CoreBundle\Entity\Event\Event $requestEvent
     *
     * @return ApproveEvent
     */
    public function setRequestEvent(\Celsius3\CoreBundle\Entity\Event\Event $requestEvent = null)
    {
        $this->requestEvent = $requestEvent;

        return $this;
    }

    /**
     * Get requestEvent
     *
     * @return \Celsius3\CoreBundle\Entity\Event\Event
     */
    public function getRequestEvent()
    {
        return $this->requestEvent;
    }
}
