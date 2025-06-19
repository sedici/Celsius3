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
use Celsius3\Repository\CountryRepository;
use Doctrine\Common\Collections\Collection;
use Gedmo\Timestampable\Traits\TimestampableEntity;

#[
    ORM\Table(name: 'country'),
    ORM\Entity(repositoryClass: CountryRepository::class),

    ORM\Index(name: 'idx_name', columns: ['name']),
    ORM\Index(name: 'idx_instance', columns: ['instance_id'])
]
class Country
{
    use TimestampableEntity;

    #[ORM\Column(type: 'integer')]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    private ?int $id = null;


    #[Assert\NotBlank]
    #[ORM\Column(type: 'string', length: 255, unique: true)]
    private string $name;


    #[Assert\NotBlank]
    #[ORM\Column(type: 'string', length: 255, unique: true)]
    private string $abbreviation;


    #[ORM\OneToMany(targetEntity: City::class, mappedBy: 'country')]
    private Collection $cities;


    #[ORM\OneToMany(targetEntity: Institution::class, mappedBy: 'country')]
    private Collection $institutions;


    #[Assert\NotNull]
    #[ORM\ManyToOne(targetEntity: Instance::class, inversedBy: 'countries')]
    #[ORM\JoinColumn(name: 'instance_id', referencedColumnName: 'id', nullable: false)]
    private Instance $instance;


    public function __toString(): string
    {
        return $this->name;
    }

    public function __construct()
    {
        $this->institutions = new ArrayCollection();
        $this->cities = new ArrayCollection();
    }

    public function getFullName(): string {
        return $this->getName();
    }

    /**
     * @return $id
     */
    public function getId(): ?int
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
    public function setName($name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string $name
     */
    public function getName(): string
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
    public function setAbbreviation($abbreviation): static
    {
        $this->abbreviation = $abbreviation;

        return $this;
    }

    /**
     * Get abbreviation.
     *
     * @return string $abbreviation
     */
    public function getAbbreviation(): string
    {
        return $this->abbreviation;
    }

    /**
     * Add cities.
     *
     * @param City $cities
     */
    public function addCitie(City $cities): void
    {
        $this->cities[] = $cities;
    }

    /**
     * Remove cities.
     *
     * @param City $cities
     */
    public function removeCitie(City $cities): void
    {
        $this->cities->removeElement($cities);
    }

    /**
     * @return Collection $cities
     */
    public function getCities(): Collection
    {
        return $this->cities;
    }

    /**
     * Add institutions.
     *
     * @param Institution $institutions
     */
    public function addInstitution(Institution $institutions): void
    {
        $this->institutions[] = $institutions;
    }

    /**
     * Remove institutions.
     *
     * @param Institution $institutions
     */
    public function removeInstitution(Institution $institutions): void
    {
        $this->institutions->removeElement($institutions);
    }

    /**
     * Get institutions.
     *
     * @return Collection $institutions
     */
    public function getInstitutions(): Collection
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
    public function setInstance(Instance $instance): static
    {
        $this->instance = $instance;

        return $this;
    }

    /**
     * Get instance.
     *
     * @return Instance $instance
     */
    public function getInstance(): Instance
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
    public function addCity(City $city): static
    {
        $this->cities[] = $city;

        return $this;
    }

    /**
     * Remove city.
     *
     * @param City $city
     */
    public function removeCity(City $city): void
    {
        $this->cities->removeElement($city);
    }
}
