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

namespace Celsius3\TicketBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Celsius3\Entity\Mixin\TimestampableEntity;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity
 * @ORM\Table(name="ticket_type_state")
 */
class TypeState
{
    public const TYPE_STATE_NEW = 1;
    public const TYPE_STATE_IN_PROGRESS = 2;
    public const TYPE_STATE_RESUELTA = 3;
    public const TYPE_STATE_RECHAZADA = 4;

    use TimestampableEntity;
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private int $id;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="string", length=255)
     */
    private string $typeState;

    public function __construct()
    {
    }

    /**
     * Get id.
     */
    public function getId(): int
    { return $this->id; }

    /**
     * Set typeState.
     */
    public function setTypeState(string $typeState): TypeState
    {
        $this->typeState = $typeState;
        return $this;
    }

    /**
     * Get typeState.
     */
    public function getTypeState(): string
    { return $this->typeState; }


    public function __toString(): string
    { return $this->typeState; }
}
