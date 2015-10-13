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

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="operator_in_request", indexes={
 *   @ORM\Index(name="idx_operator", columns={"operator"}),
 *   @ORM\Index(name="idx_request", columns={"request"}),
 *   @ORM\Index(name="idx_instance", columns={"instance"})
 * })
 */
class OperatorInRequest
{

    use TimestampableEntity;

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Celsius3\CoreBundle\Entity\BaseUser")
     * @ORM\JoinColumn(name="operator",referencedColumnName="id")
     */
    private $operator;

    /**
     * @ORM\ManyToOne(targetEntity="Celsius3\CoreBundle\Entity\Request")
     * @ORM\JoinColumn(name="request",referencedColumnName="id")
     */
    private $request;

    /**
     * @ORM\ManyToOne(targetEntity="Celsius3\CoreBundle\Entity\Instance")
     * @ORM\JoinColumn(name="instance",referencedColumnName="id")
     */
    private $instance;

    /**
     * @ORM\Column(type="boolean", name="working")
     */
    private $working;

    /**
     * Set operator
     *
     * @param \Celsius3\CoreBundle\Entity\BaseUser $operator
     *
     * @return OperatorInRequest
     */
    public function setOperator(\Celsius3\CoreBundle\Entity\BaseUser $operator)
    {
        $this->operator = $operator;

        return $this;
    }

    /**
     * Get operator
     *
     * @return \Celsius3\CoreBundle\Entity\BaseUser
     */
    public function getOperator()
    {
        return $this->operator;
    }

    /**
     * Set request
     *
     * @param \Celsius3\CoreBundle\Entity\Request $request
     *
     * @return OperatorInRequest
     */
    public function setRequest(\Celsius3\CoreBundle\Entity\Request $request)
    {
        $this->request = $request;

        return $this;
    }

    /**
     * Get request
     *
     * @return \Celsius3\CoreBundle\Entity\Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * Set working
     *
     * @param boolean $working
     *
     * @return OperatorInRequest
     */
    public function setWorking($working)
    {
        $this->working = $working;

        return $this;
    }

    /**
     * Get working
     *
     * @return boolean
     */
    public function getWorking()
    {
        return $this->working;
    }

    /**
     * Set instance
     *
     * @param \Celsius3\CoreBundle\Entity\Instance $instance
     *
     * @return OperatorInRequest
     */
    public function setInstance(\Celsius3\CoreBundle\Entity\Instance $instance)
    {
        $this->instance = $instance;

        return $this;
    }

    /**
     * Get instance
     *
     * @return \Celsius3\CoreBundle\Entity\Instance
     */
    public function getInstance()
    {
        return $this->instance;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

}
