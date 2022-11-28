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

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="Celsius3\Repository\BaseRepository")
 * @ORM\Table(name="hive", indexes={
 *   @ORM\Index(name="idx_name", columns={"name"})
 * })
 */
class Hive
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
     * @Assert\NotNull
     * @ORM\Column(type="string", length=255)
     */
    private $name;
    /**
     * @ORM\OneToMany(targetEntity="LegacyInstance", mappedBy="hive")
     */
    private $instances;
    /**
     * @ORM\OneToMany(targetEntity="Institution", mappedBy="hive")
     */
    private $institutions;

    public function __toString()
    {
        return $this->name;
    }

    public function __construct()
    {
        $this->instances = new ArrayCollection();
        $this->institutions = new ArrayCollection();
    }

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
     * Set name.
     *
     * @param string $name
     *
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string $name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Add instance.
     *
     * @param LegacyInstance $instance
     */
    public function addInstance(LegacyInstance $instance)
    {
        $this->instances[] = $instance;
    }

    /**
     * Remove instance.
     *
     * @param LegacyInstance $instance
     */
    public function removeInstance(LegacyInstance $instance)
    {
        $this->instances->removeElement($instance);
    }

    /**
     * Get instances.
     *
     * @return Collection $instances
     */
    public function getInstances()
    {
        return $this->instances;
    }

    /**
     * Add institution.
     *
     * @param Institution $institution
     */
    public function addInstitution(Institution $institution)
    {
        $this->institutions[] = $institution;
    }

    /**
     * Remove institution.
     *
     * @param Institution $institution
     */
    public function removeInstitution(Institution $institution)
    {
        $this->institutions->removeElement($institution);
    }

    /**
     * Get institutions.
     *
     * @return Collection $institutions
     */
    public function getInstitutions()
    {
        return $this->institutions;
    }
}
