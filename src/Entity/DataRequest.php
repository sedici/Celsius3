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

namespace Celsius3\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="Celsius3\Repository\DataRequestRepository")
 * @ORM\Table(name="data_request")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap({
 *   "users_data_request"="UsersDataRequest",
 *   "orders_data_request"="OrdersDataRequest"
 * })
 */
abstract class DataRequest
{
    use TimestampableEntity;

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     */
    protected $name;

    /**
     * @ORM\Column(name="start_date", type="date")
     * @Assert\Date()
     */
    protected $startDate;

    /**
     * @ORM\Column(name="end_date", type="date")
     * @Assert\Date()
     */
    protected $endDate;

    /**
     * @ORM\Column(type="text", nullable=false)
     * @Assert\NotNull()
     */
    protected $data;

    /**
     * @ORM\ManyToOne(targetEntity="Instance", inversedBy="dataRequests")
     * @ORM\JoinColumn(name="instance_id", referencedColumnName="id")
     */
    private $instance;

    /**
     * @ORM\Column(type="boolean")
     */
    private $exported = false;

    /**
     * @ORM\Column(type="boolean")
     */
    private $downloaded = false;

    /**
     * @ORM\Column(type="boolean")
     */
    private $visible = true;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $file;

    /**
     * DataRequest constructor.
     *
     * @param $instance Instance
     */
    public function __construct($instance)
    {
        $this->instance = $instance;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param string $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    public function getArrayData()
    {
        return unserialize($this->data);
    }

    /**
     * @return Instance
     */
    public function getInstance()
    {
        return $this->instance;
    }

    /**
     * @param Instance $instance
     */
    public function setInstance($instance)
    {
        $this->instance = $instance;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return DataRequest
     */
    public function setName(string $name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * @param mixed $startDate
     * @return DataRequest
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * @param mixed $endDate
     * @return DataRequest
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getExported()
    {
        return $this->exported;
    }

    /**
     * @param mixed $exported
     */
    public function setExported($exported)
    {
        $this->exported = $exported;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDownloaded()
    {
        return $this->downloaded;
    }

    /**
     * @param mixed $downloaded
     */
    public function setDownloaded($downloaded)
    {
        $this->downloaded = $downloaded;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param mixed $file
     */
    public function setFile($file)
    {
        $this->file = $file;
    }

    /**
     * @return mixed
     */
    public function getVisible()
    {
        return $this->visible;
    }

    /**
     * @param mixed $visible
     */
    public function setVisible($visible)
    {
        $this->visible = $visible;

        return $this;
    }
}
