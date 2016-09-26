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

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Gedmo\Mapping\Annotation as Gedmo;

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
     * @ORM\ManyToOne(targetEntity="Celsius3\CoreBundle\Entity\BaseUser")
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
     * @ORM\Column(type="string")
     */
    private $descripcion;

    /**
     * Get id.
     *
     * @return \Celsius3\TicketBundle\Entity\TypeState
     */
    private $createdAt;



    /**
     * Set typeState.
     *
     * @param \Celsius3\TicketBundle\Entity\TypeState $typeState
     *
     * @return TicketState
     */
    public function setTypeState(\Celsius3\TicketBundle\Entity\TypeState $typeState = null)
    {
        $this->typeState = $typeState;



    public function __construct()
    {
    }

    /**
     * Get typeState.
     *
     * @return \Celsius3\TicketBundle\Entity\TypeState
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set user
     *
     * @param \Celsius3\CoreBundle\Entity\BaseUser $user
     *
     * @return TicketState
     */
    public function setUser(\Celsius3\CoreBundle\Entity\BaseUser $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Celsius3\CoreBundle\Entity\BaseUser
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set tickets
     *
     * @param \Celsius3\TicketBundle\Entity\Ticket $tickets
     *
     * @return TicketState
     */
    public function setTickets(\Celsius3\TicketBundle\Entity\Ticket $tickets)
    {
        $this->tickets = $tickets;

        return $this;
    }

    /**
     * Get tickets
     *
     * @return \Celsius3\TicketBundle\Entity\Ticket
     */
    public function getTickets()
    {
        return $this->tickets;
    }

    /**
     * Set descripcion
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
     * Get descripcion
     *
     * @return string
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }
}
