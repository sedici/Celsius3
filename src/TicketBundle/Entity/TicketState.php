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

use Celsius3\Entity\BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity("")
 * @ORM\Table(name="ticket_state")
 */
class TicketState
{
    use TimestampableEntity;

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var Baseuser
     *
     * @Gedmo\Blameable(on="create")
     * @ORM\ManyToOne(targetEntity="Celsius3\Entity\BaseUser")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

    /**
     * @ORM\ManyToOne(targetEntity="Celsius3\TicketBundle\Entity\TypeState")
     * @ORM\JoinColumn(name="type_state_id", referencedColumnName="id")
     */
    private $typeState;

    /**
     * @ORM\ManyToOne(targetEntity="Celsius3\TicketBundle\Entity\Ticket", inversedBy="statusHistory")
     * @ORM\JoinColumn(name="ticket_id", referencedColumnName="id", nullable=false)
     */
    private $tickets;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $descripcion;

    /**
     * Get id.
     *
     * @return TypeState
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set typeState.
     *
     * @param TypeState $typeState
     *
     * @return TicketState
     */
    public function setTypeState(TypeState $typeState = null)
    {
        $this->typeState = $typeState;

        return $this;
    }

    /**
     * Get typeState.
     *
     * @return TypeState
     */
    public function getTypeState()
    {
        return $this->typeState;
    }

    /**
     * Set user.
     *
     * @param BaseUser $user
     *
     * @return TicketState
     */
    public function setUser(BaseUser $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user.
     *
     * @return BaseUser
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set tickets.
     * Set tickets.
     *
     * @param Ticket $tickets
     *
     * @return TicketState
     */
    public function setTickets(Ticket $tickets)
    {
        $this->tickets = $tickets;

        return $this;
    }

    /**
     * Get tickets.
     *
     * @return Ticket
     */
    public function getTickets()
    {
        return $this->tickets;
    }

    /**
     * Set descripcion.
     *
     * @param string $descripcion
     *
     * @return TicketState
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Get descripcion.
     *
     * @return string
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }
}
