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

use Celsius3\CoreBundle\Entity\BaseUser;
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
class UploadEvent extends MultiInstanceEvent implements Notifiable
{
    use ReclaimableTrait;
    use ApprovableTrait;

    /**
     * @Assert\NotBlank
     * @ORM\Column(type="string", length=255)
     */
    private $deliveryType;

    /**
     * @Assert\NotNull
     * @ORM\ManyToOne(
     *     targetEntity="Celsius3\CoreBundle\Entity\State",
     *     inversedBy="remoteEvents",
     *     cascade={"persist",  "refresh"})
     * @ORM\JoinColumn(name="remote_state_id", referencedColumnName="id")
     */
    private $remoteState;

    /**
     * @ORM\ManyToMany(targetEntity="Celsius3\CoreBundle\Entity\File", cascade={"persist"})
     * @ORM\JoinTable(
     *     name="uploads_files",
     *     joinColumns={@ORM\JoinColumn(name="event_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="file_id", referencedColumnName="id", unique=true)}
     * )
     */
    private $files;

    public function __construct()
    {
        $this->files = new ArrayCollection();
    }

    public function getEventType(): string
    {
        return 'upload';
    }

    public function applyExtraData(Request $request, array $data, LifecycleHelper $lifecycleHelper, $date): void
    {
        $this->setDeliveryType('pdf');
        $lifecycleHelper->uploadFiles($request, $this, $data['extraData']['files']);
        $this->setRemoteInstance($request->getPreviousRequest()->getInstance());
        $data['instance'] = $this->getRemoteInstance();
        $data['stateName'] = StateManager::STATE__APPROVAL_PENDING;
        $this->setRemoteState($lifecycleHelper->getState($request->getPreviousRequest(), $data, $this));
    }

    public function getDeliveryType(): string
    {
        return $this->deliveryType;
    }

    public function setDeliveryType($deliveryType): self
    {
        $this->deliveryType = $deliveryType;

        return $this;
    }

    public function addFile(File $files): void
    {
        $this->files[] = $files;
    }

    public function removeFile(File $files): void
    {
        $this->files->removeElement($files);
    }

    public function getFiles()
    {
        return $this->files;
    }

    public function getRemoteState(): State
    {
        return $this->remoteState;
    }

    public function setRemoteState(State $remoteState): self
    {
        $this->remoteState = $remoteState;

        return $this;
    }

    public function notify(NotificationManager $manager): void
    {
        $manager->notifyRemoteEvent($this, 'upload');
    }

    public function getRemoteNotificationTarget(): BaseUser
    {
        return $this->getRequest()->getPreviousRequest()->getOwner();
    }

    public function getReclaimed(): bool
    {
        return $this->reclaimed;
    }

    public function getApproved(): bool
    {
        return $this->approved;
    }
}
