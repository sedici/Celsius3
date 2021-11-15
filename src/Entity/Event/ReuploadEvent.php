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

use Celsius3\CoreBundle\Entity\Request;
use Celsius3\Helper\LifecycleHelper;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="Celsius3\Repository\BaseRepository")
 */
class ReuploadEvent extends SingleInstanceEvent
{
    /**
     * @Assert\NotNull
     * @ORM\ManyToOne(targetEntity="Celsius3\Entity\Event\Event")
     * @ORM\JoinColumn(name="receive_event_id", referencedColumnName="id")
     */
    private $receiveEvent;

    public function getEventType(): string
    {
        return 'reupload';
    }

    public function applyExtraData(Request $request, array $data, LifecycleHelper $lifecycleHelper, $date): void
    {
        $this->setReceiveEvent($data['extraData']['receive']);
        $this->getReceiveEvent()->setReclaimed(false);
        $lifecycleHelper->refresh($this->getReceiveEvent());
        $lifecycleHelper->uploadFiles($request, $this->getReceiveEvent(), $data['extraData']['files']);
    }

    public function getReceiveEvent(): Event
    {
        return $this->receiveEvent;
    }

    public function setReceiveEvent(Event $receiveEvent): self
    {
        $this->receiveEvent = $receiveEvent;

        return $this;
    }
}
