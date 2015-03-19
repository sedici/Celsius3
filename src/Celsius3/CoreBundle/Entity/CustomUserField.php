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
 * @ORM\Table(name="custom_user_field", uniqueConstraints={
 *   @ORM\UniqueConstraint(name="unique_idx", columns={"key", "instance_id"})
 * }, indexes={
 *   @ORM\Index(name="idx_key", columns={"key"}),
 *   @ORM\Index(name="idx_name", columns={"name"}),
 *   @ORM\Index(name="idx_instance", columns={"instance_id"})
 * })
 */
class CustomUserField
{

    use TimestampableEntity;
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @Gedmo\Slug(fields={"name"})
     * @ORM\Column(type="string", length=255, name="`key`")
     */
    private $key;
    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="string", length=255)
     */
    private $name;
    /**
     * @Assert\Type(type="boolean")
     * @ORM\Column(type="boolean")
     */
    private $private = true;
    /**
     * @Assert\Type(type="boolean")
     * @ORM\Column(type="boolean")
     */
    private $required = true;
    /**
     * @ORM\ManyToOne(targetEntity="Instance")
     * @ORM\JoinColumn(name="instance_id", referencedColumnName="id", nullable=false)
     */
    private $instance;
    /**
     * @ORM\OneToMany(targetEntity="CustomUserValue", mappedBy="field")
     */
    private $values;

    public function __construct()
    {
        $this->values = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set key
     *
     * @param  string $key
     * @return self
     */
    public function setKey($key)
    {
        $this->key = $key;

        return $this;
    }

    /**
     * Get key
     *
     * @return string $key
     */
    public function getKey()
    {
        return $this->key;
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
     * Set private
     *
     * @param  boolean $private
     * @return self
     */
    public function setPrivate($private)
    {
        $this->private = $private;

        return $this;
    }

    /**
     * Get private
     *
     * @return boolean $private
     */
    public function getPrivate()
    {
        return $this->private;
    }

    /**
     * Set required
     *
     * @param  boolean $required
     * @return self
     */
    public function setRequired($required)
    {
        $this->required = $required;

        return $this;
    }

    /**
     * Get required
     *
     * @return boolean $required
     */
    public function getRequired()
    {
        return $this->required;
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
     * Add values
     *
     * @param Celsius3\CoreBundle\Entity\CustomUserValue $values
     */
    public function addValue(\Celsius3\CoreBundle\Entity\CustomUserValue $values)
    {
        $this->values[] = $values;
    }

    /**
     * Remove values
     *
     * @param Celsius3\CoreBundle\Entity\CustomUserValue $values
     */
    public function removeValue(\Celsius3\CoreBundle\Entity\CustomUserValue $values)
    {
        $this->values->removeElement($values);
    }

    /**
     * Get values
     *
     * @return Doctrine\Common\Collections\Collection $values
     */
    public function getValues()
    {
        return $this->values;
    }
}
