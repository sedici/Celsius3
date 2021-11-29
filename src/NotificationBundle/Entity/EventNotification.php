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

namespace Celsius3\NotificationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="Celsius3\NotificationBundle\Repository\BaseRepository")
 */
class EventNotification extends Notification
{
    /**
     * @Assert\NotNull
     * @ORM\ManyToOne(targetEntity="Celsius3\Entity\Event\Event")
     * @ORM\JoinColumn(name="event_notification_id", referencedColumnName="id")
     */
    protected $object;

    public function __construct($cause, $object, $template)
    {
        parent::__construct();

        $this->setCause($cause);
        $this->setObject($object);
        $this->setTemplate($template);
    }

    public function setObject($object)
    {
        $this->object = $object;
    }
}
