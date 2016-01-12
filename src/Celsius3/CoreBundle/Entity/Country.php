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
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="Celsius3\CoreBundle\Repository\CountryRepository")
 * @ORM\Table(name="country", indexes={
 *   @ORM\Index(name="idx_name", columns={"name"}),
 *   @ORM\Index(name="idx_instance", columns={"instance_id"})
 * })
 */
class Country
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
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $name;
    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $abbreviation;
    /**
     * @ORM\OneToMany(targetEntity="City", mappedBy="country")
     */
    private $cities;
    /**
     * @ORM\OneToMany(targetEntity="Institution", mappedBy="country")
     */
    private $institutions;
    /**
     * @Assert\NotNull
     * @ORM\ManyToOne(targetEntity="Instance", inversedBy="countries")
     * @ORM\JoinColumn(name="instance_id", referencedColumnName="id", nullable=false)
     */
    private $instance;

    public function __toString()
    {
        return $this->name;
    }

    public function __construct()
    {
        $this->institutions = new \Doctrine\Common\Collections\ArrayCollection();
        $this->cities = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set abbreviation
     *
     * @param  string $abbreviation
     * @return self
     */
    public function setAbbreviation($abbreviation)
    {
        $this->abbreviation = $abbreviation;

        return $this;
    }

    /**
     * Get abbreviation
     *
     * @return string $abbreviation
     */
    public function getAbbreviation()
    {
        return $this->abbreviation;
    }

    /**
     * Add cities
     *
     * @param Celsius3\CoreBundle\Entity\City $cities
     */
    public function addCitie(\Celsius3\CoreBundle\Entity\City $cities)
    {
        $this->cities[] = $cities;
    }

    /**
     * Remove cities
     *
     * @param Celsius3\CoreBundle\Entity\City $cities
     */
    public function removeCitie(\Celsius3\CoreBundle\Entity\City $cities)
    {
        $this->cities->removeElement($cities);
    }

    /**
     * Get cities
     *
     * @return Doctrine\Common\Collections\Collection $cities
     */
    public function getCities()
    {
        return $this->cities;
    }

    /**
     * Add institutions
     *
     * @param Celsius3\CoreBundle\Entity\Institution $institutions
     */
    public function addInstitution(\Celsius3\CoreBundle\Entity\Institution $institutions)
    {
        $this->institutions[] = $institutions;
    }

    /**
     * Remove institutions
     *
     * @param Celsius3\CoreBundle\Entity\Institution $institutions
     */
    public function removeInstitution(\Celsius3\CoreBundle\Entity\Institution $institutions)
    {
        $this->institutions->removeElement($institutions);
    }

    /**
     * Get institutions
     *
     * @return Doctrine\Common\Collections\Collection $institutions
     */
    public function getInstitutions()
    {
        return $this->institutions;
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
}
