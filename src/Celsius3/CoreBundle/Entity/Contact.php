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

namespace Celsius3\CoreBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="contact")
 */
class Contact
{

    use TimestampableEntity;
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="string", length=255)
     */
    private $name;
    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="string", length=255)
     */
    private $surname;
    /**
     * @Assert\NotBlank()
     * @Assert\Email()
     * @ORM\Column(type="string", length=255)
     */
    private $email;
    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="string", length=255)
     */
    private $address;
    /**
     * @ORM\OneToOne(targetEntity="BaseUser")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;
    /**
     * @Assert\NotNull
     * @ORM\ManyToOne(targetEntity="ContactType", inversedBy="contacts")
     * @ORM\JoinColumn(name="type_id", referencedColumnName="id", nullable=false)
     */
    private $type;
    /**
     * @Assert\NotNull
     * @ORM\ManyToOne(targetEntity="Instance", inversedBy="contacts")
     * @ORM\JoinColumn(name="instance_id", referencedColumnName="id", nullable=false)
     */
    private $instance;
    /**
     * @Assert\NotNull
     * @ORM\ManyToOne(targetEntity="Institution", inversedBy="contacts")
     * @ORM\JoinColumn(name="institution_id", referencedColumnName="id", nullable=false)
     */
    private $institution;
    /**
     * @Assert\NotNull
     * @ORM\ManyToOne(targetEntity="Instance")
     * @ORM\JoinColumn(name="owning_instance_id", referencedColumnName="id", nullable=false)
     */
    private $owningInstance;

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
     * Set name
     *
     * @param  string $name
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string $name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set surname
     *
     * @param  string $surname
     * @return self
     */
    public function setSurname($surname)
    {
        $this->surname = $surname;

        return $this;
    }

    /**
     * Get surname
     *
     * @return string $surname
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * Set email
     *
     * @param  string $email
     * @return self
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string $email
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set address
     *
     * @param  string $address
     * @return self
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string $address
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set user
     *
     * @param  Celsius3\CoreBundle\Entity\BaseUser $user
     * @return self
     */
    public function setUser(\Celsius3\CoreBundle\Entity\BaseUser $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return Celsius3\CoreBundle\Entity\BaseUser $user
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set type
     *
     * @param  Celsius3\CoreBundle\Entity\ContactType $type
     * @return self
     */
    public function setType(\Celsius3\CoreBundle\Entity\ContactType $type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return Celsius3\CoreBundle\Entity\ContactType $type
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set instance
     *
     * @param  Celsius3\CoreBundle\Entity\Instance $instance
     * @return self
     */
    public function setInstance(\Celsius3\CoreBundle\Entity\Instance $instance)
    {
        $this->instance = $instance;

        return $this;
    }

    /**
     * Get instance
     *
     * @return Celsius3\CoreBundle\Entity\Instance $instance
     */
    public function getInstance()
    {
        return $this->instance;
    }

    /**
     * Set institution
     *
     * @param  Celsius3\CoreBundle\Entity\Institution $institution
     * @return self
     */
    public function setInstitution(\Celsius3\CoreBundle\Entity\Institution $institution)
    {
        $this->institution = $institution;

        return $this;
    }

    /**
     * Get institution
     *
     * @return Celsius3\CoreBundle\Entity\Institution $institution
     */
    public function getInstitution()
    {
        return $this->institution;
    }

    /**
     * Set owningInstance
     *
     * @param  Celsius3\CoreBundle\Entity\Instance $owningInstance
     * @return self
     */
    public function setOwningInstance(\Celsius3\CoreBundle\Entity\Instance $owningInstance)
    {
        $this->owningInstance = $owningInstance;

        return $this;
    }

    /**
     * Get owningInstance
     *
     * @return Celsius3\CoreBundle\Entity\Instance $owningInstance
     */
    public function getOwningInstance()
    {
        return $this->owningInstance;
    }
}
