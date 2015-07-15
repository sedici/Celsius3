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

namespace Celsius3\NotificationBundle\Entity;

use Celsius3\NotificationBundle\Entity\Notification;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Celsius3\MessageBundle\Entity\Message;

/**
 * @ORM\Entity
 */
class MessageNotification extends Notification
{

    /**
     * @Assert\NotNull
     * @ORM\ManyToOne(targetEntity="Celsius3\MessageBundle\Entity\Message")
     * @ORM\JoinColumn(name="message_notification_id", referencedColumnName="id")
     */
    private $object;

    function __construct($cause, Message $object, $template)
    {
        parent::__construct();

        $this->setCause($cause);
        $this->setObject($object);
        $this->setTemplate($template);
    }

    function getObject()
    {
        return $this->object;
    }

    function setObject($object)
    {
        $this->object = $object;
    }

}
