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
use Celsius3\CoreBundle\Manager\StateManager;
use Celsius3\CoreBundle\Entity\Mixin\ReclaimableTrait;
use Celsius3\CoreBundle\Entity\Mixin\ApprovableTrait;
use Celsius3\CoreBundle\Entity\Request;

/**
 * @ORM\Entity
 */
class UploadEvent extends MultiInstanceEvent
{
    use ReclaimableTrait,
        ApprovableTrait;
    /**
     * @Assert\NotNull
     * @ORM\ManyToOne(targetEntity="Celsius3\CoreBundle\Entity\State", inversedBy="remoteEvents", cascade={"persist",  "refresh"})
     * @ORM\JoinColumn(name="remote_state_id", referencedColumnName="id")
     */
    private $remoteState;
    /**
     * @ORM\OneToMany(targetEntity="Celsius3\CoreBundle\Entity\File", mappedBy="event")
     */
    private $files;

    public function __construct()
    {
        $this->files = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function applyExtraData(Request $request, array $data, LifecycleHelper $lifecycleHelper, $date)
    {
        $lifecycleHelper->uploadFiles($request, $this, $data['extraData']['files']);
        $this->setRemoteInstance($request->getPreviousRequest()->getInstance());
        $data['instance'] = $this->getRemoteInstance();
        $data['stateName'] = StateManager::STATE__APPROVAL_PENDING;
        $this->setRemoteState($lifecycleHelper->getState($request->getPreviousRequest(), $this, $data, $this));
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
     * Set remoteState
     *
     * @param  Celsius3\CoreBundle\Entity\State $remoteState
     * @return self
     */
    public function setRemoteState(\Celsius3\CoreBundle\Entity\State $remoteState)
    {
        $this->remoteState = $remoteState;

        return $this;
    }

    /**
     * Get remoteState
     *
     * @return Celsius3\CoreBundle\Entity\State $remoteState
     */
    public function getRemoteState()
    {
        return $this->remoteState;
    }
}