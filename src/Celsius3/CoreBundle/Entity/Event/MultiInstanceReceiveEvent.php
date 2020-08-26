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

use Celsius3\CoreBundle\Entity\File;
use Celsius3\CoreBundle\Entity\Mixin\ApprovableTrait;
use Celsius3\CoreBundle\Entity\Mixin\ReclaimableTrait;
use Celsius3\CoreBundle\Entity\Request;
use Celsius3\CoreBundle\Entity\State;
use Celsius3\CoreBundle\Helper\LifecycleHelper;
use Celsius3\CoreBundle\Manager\StateManager;
use Celsius3\NotificationBundle\Entity\Notifiable;
use Celsius3\NotificationBundle\Manager\NotificationManager;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="Celsius3\CoreBundle\Repository\BaseRepository")
 */
class MultiInstanceReceiveEvent extends MultiInstanceEvent implements Notifiable
{
    use ReclaimableTrait;
    use ApprovableTrait;

    /**
     * @Assert\NotBlank
     * @ORM\Column(type="string", length=255)
     */
    private $deliveryType;

    /**
     * @ORM\ManyToMany(targetEntity="Celsius3\CoreBundle\Entity\File", cascade={"persist"})
     * @ORM\JoinTable(name="mirequests_files",
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
     * @ORM\ManyToOne(targetEntity="Celsius3\CoreBundle\Entity\Event\Event")
     * @ORM\JoinColumn(name="request_event_id", referencedColumnName="id")
     */
    private $requestEvent;

    public function getEventType()
    {
        return 'mireceive';
    }

    public function __construct()
    {
        $this->files = new ArrayCollection();
    }

    public function applyExtraData(Request $request, array $data, LifecycleHelper $lifecycleHelper, $date)
    {
        $this->setDeliveryType($data['extraData']['delivery_type']);
        $this->setRequestEvent($data['extraData']['request']);
        $this->setObservations($data['extraData']['observations']);
        $lifecycleHelper->uploadFiles($request, $this, $data['extraData']['files']);
        $this->setRemoteInstance($request->getPreviousRequest()->getInstance());
        $data['instance'] = $this->getRemoteInstance();
        $data['stateName'] = StateManager::STATE__APPROVAL_PENDING;
        $this->setRemoteState($lifecycleHelper->getState($request->getPreviousRequest(), $data, $this));
    }

    /**
     * Set deliveryType.
     *
     * @param string $deliveryType
     *
     * @return self
     */
    public function setDeliveryType($deliveryType)
    {
        $this->deliveryType = $deliveryType;

        return $this;
    }

    /**
     * Get deliveryType.
     *
     * @return string $deliveryType
     */
    public function getDeliveryType()
    {
        return $this->deliveryType;
    }

    /**
     * Add files.
     *
     * @param File $files
     */
    public function addFile(File $files)
    {
        $this->files[] = $files;
    }

    /**
     * Remove files.
     *
     * @param File $files
     */
    public function removeFile(File $files)
    {
        $this->files->removeElement($files);
    }

    /**
     * Get files.
     *
     * @return Collection $files
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * Set remoteState.
     *
     * @param State $remoteState
     *
     * @return self
     */
    public function setRemoteState(State $remoteState)
    {
        $this->remoteState = $remoteState;

        return $this;
    }

    /**
     * Get remoteState.
     *
     * @return State $remoteState
     */
    public function getRemoteState()
    {
        return $this->remoteState;
    }

    /**
     * Set requestEvent.
     *
     * @param Event $requestEvent
     *
     * @return self
     */
    public function setRequestEvent(Event $requestEvent)
    {
        $this->requestEvent = $requestEvent;

        return $this;
    }

    /**
     * Get requestEvent.
     *
     * @return Event $requestEvent
     */
    public function getRequestEvent()
    {
        return $this->requestEvent;
    }

    public function notify(NotificationManager $manager)
    {
        $manager->notifyEvent($this, 'receive');
    }

    public function getRemoteNotificationTarget()
    {
        return $this->getRequest()->getPreviousRequest()->getOwner();
    }

    /**
     * Get reclaimed.
     *
     * @return bool
     */
    public function getReclaimed()
    {
        return $this->reclaimed;
    }

    /**
     * Get approved.
     *
     * @return bool
     */
    public function getApproved()
    {
        return $this->approved;
    }
}
