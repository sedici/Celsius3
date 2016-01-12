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
use Celsius3\CoreBundle\Manager\EventManager;

/**
 * @ORM\Entity
 */
class ApproveEvent extends MultiInstanceEvent
{
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
     * @ORM\OneToOne(targetEntity="Celsius3\CoreBundle\Entity\Event\Event")
     * @ORM\JoinColumn(name="receive_event_id", referencedColumnName="id")
     */
    private $receiveEvent;

    public function applyExtraData(Request $request, array $data, LifecycleHelper $lifecycleHelper, $date)
    {
        $this->setReceiveEvent($data['extraData']['receive']);
        $this->getReceiveEvent()->setIsApproved(true);
        $lifecycleHelper->refresh($this->getReceiveEvent());
        $lifecycleHelper->copyFilesToPreviousRequest($request, $data['extraData']['receive']->getRequest(), $this);
        $lifecycleHelper->createEvent(EventManager::EVENT__DELIVER, $data['extraData']['receive']->getRequest(), $data['extraData']['receive']->getRequest()->getInstance());
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
}
