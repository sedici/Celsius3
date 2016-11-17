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

namespace Celsius3\CoreBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass="Celsius3\CoreBundle\Repository\BaseRepository")
 * @ORM\Table(name="file_download", indexes={
 *   @ORM\Index(name="idx_request", columns={"request_id"}),
 *   @ORM\Index(name="idx_user", columns={"user_id"}),
 *   @ORM\Index(name="idx_ip", columns={"ip"}),
 *   @ORM\Index(name="idx_instance", columns={"instance_id"})
 * })
 */
class FileDownload
{
    use TimestampableEntity;

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @Assert\NotBlank
     * @Assert\Ip
     * @ORM\Column(type="string", length=255)
     */
    private $ip;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $userAgent;

    /**
     * @Assert\NotNull
     * @ORM\ManyToOne(targetEntity="BaseUser")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */
    private $user;

    /**
     * @Assert\NotNull
     * @ORM\ManyToOne(targetEntity="File", inversedBy="downloads")
     * @ORM\JoinColumn(name="file_id", referencedColumnName="id", nullable=false)
     */
    private $file;

    /**
     * @Assert\NotNull
     * @ORM\ManyToOne(targetEntity="Request")
     * @ORM\JoinColumn(name="request_id", referencedColumnName="id", nullable=false)
     */
    private $request;

    /**
     * @Assert\NotNull
     * @ORM\ManyToOne(targetEntity="Instance")
     * @ORM\JoinColumn(name="instance_id", referencedColumnName="id", nullable=false)
     */
    private $instance;

    /**
     * Get id.
     *
     * @return id $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set ip.
     *
     * @param string $ip
     *
     * @return self
     */
    public function setIp($ip)
    {
        $this->ip = $ip;

        return $this;
    }

    /**
     * Get ip.
     *
     * @return string $ip
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * Set userAgent.
     *
     * @param string $userAgent
     *
     * @return self
     */
    public function setUserAgent($userAgent)
    {
        $this->userAgent = $userAgent;

        return $this;
    }

    /**
     * Get userAgent.
     *
     * @return string $userAgent
     */
    public function getUserAgent()
    {
        return $this->userAgent;
    }

    /**
     * Set user.
     *
     * @param Celsius3\CoreBundle\Entity\BaseUser $user
     *
     * @return self
     */
    public function setUser(\Celsius3\CoreBundle\Entity\BaseUser $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user.
     *
     * @return Celsius3\CoreBundle\Entity\BaseUser $user
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set file.
     *
     * @param Celsius3\CoreBundle\Entity\File $file
     *
     * @return self
     */
    public function setFile(\Celsius3\CoreBundle\Entity\File $file)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * Get file.
     *
     * @return Celsius3\CoreBundle\Entity\File $file
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Set request.
     *
     * @param Celsius3\CoreBundle\Entity\Request $request
     *
     * @return self
     */
    public function setRequest(\Celsius3\CoreBundle\Entity\Request $request)
    {
        $this->request = $request;

        return $this;
    }

    /**
     * Get request.
     *
     * @return Celsius3\CoreBundle\Entity\Request $request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * Set instance.
     *
     * @param Celsius3\CoreBundle\Entity\Instance $instance
     *
     * @return self
     */
    public function setInstance(\Celsius3\CoreBundle\Entity\Instance $instance)
    {
        $this->instance = $instance;

        return $this;
    }

    /**
     * Get instance.
     *
     * @return Celsius3\CoreBundle\Entity\Instance $instance
     */
    public function getInstance()
    {
        return $this->instance;
    }
}
