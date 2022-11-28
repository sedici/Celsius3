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
 * @ORM\Entity(repositoryClass="Celsius3\Repository\CountryRepository")
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
        $this->institutions = new ArrayCollection();
        $this->cities = new ArrayCollection();
    }

    public function getFullName() {
        return $this->getName();
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
     * Set abbreviation.
     *
     * @param string $abbreviation
     *
     * @return self
     */
    public function setAbbreviation($abbreviation)
    {
        $this->abbreviation = $abbreviation;

        return $this;
    }

    /**
     * Get abbreviation.
     *
     * @return string $abbreviation
     */
    public function getAbbreviation()
    {
        return $this->abbreviation;
    }

    /**
     * Add cities.
     *
     * @param City $cities
     */
    public function addCitie(City $cities)
    {
        $this->cities[] = $cities;
    }

    /**
     * Remove cities.
     *
     * @param City $cities
     */
    public function removeCitie(City $cities)
    {
        $this->cities->removeElement($cities);
    }

    /**
     * Get cities.
     *
     * @return Collection $cities
     */
    public function getCities()
    {
        return $this->cities;
    }

    /**
     * Add institutions.
     *
     * @param Institution $institutions
     */
    public function addInstitution(Institution $institutions)
    {
        $this->institutions[] = $institutions;
    }

    /**
     * Remove institutions.
     *
     * @param Institution $institutions
     */
    public function removeInstitution(Institution $institutions)
    {
        $this->institutions->removeElement($institutions);
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

    /**
     * Set instance.
     *
     * @param Instance $instance
     *
     * @return self
     */
    public function setInstance(Instance $instance)
    {
        $this->instance = $instance;

        return $this;
    }

    /**
     * Get instance.
     *
     * @return Instance $instance
     */
    public function getInstance()
    {
        return $this->instance;
    }

    /**
     * Add city.
     *
     * @param City $city
     *
     * @return Country
     */
    public function addCity(City $city)
    {
        $this->cities[] = $city;

        return $this;
    }

    /**
     * Remove city.
     *
     * @param City $city
     */
    public function removeCity(City $city)
    {
        $this->cities->removeElement($city);
    }
}
