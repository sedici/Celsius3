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
     *
     * @ORM\ManyToOne(targetEntity="Celsius3\TicketBundle\Entity\TypeState")
     * @ORM\JoinColumn(name="type_state_id", referencedColumnName="id")
     */
    private $typeState;


    /**
     * @ORM\ManyToOne(targetEntity="Celsius3\TicketBundle\Entity\Ticket", inversedBy="statusHistoru")
     * @ORM\JoinColumn(name="ticket_id", referencedColumnName="id", nullable=false)
     */
    private $tickets;


    /**
     * @var \DateTime $createdAt
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime $updatedAt
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="updated_at", type="datetime")
     */
    private $updatedAt;


    public function __construct()
    {
    }

    /**
     * Get id
     *
     * @return id $id
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
     * Set typeState
     *
     * @param \Celsius3\TicketBundle\Entity\TypeState $typeState
     *
     * @return TicketState
     */
    public function setTypeState(\Celsius3\TicketBundle\Entity\TypeState $typeState = null)
    {
        $this->typeState = $typeState;

        return $this;
    }

    /**
     * Get typeState
     *
     * @return \Celsius3\TicketBundle\Entity\TypeState
     */
    public function getTypeState()
    {
        return $this->typeState;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return TicketState
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return TicketState
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
}
