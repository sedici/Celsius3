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
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;


use Celsius3\Entity\TimestampableEntity;


#[ORM\Entity(repositoryClass: \Celsius3\Repository\CityRepository::class)]
#[ORM\Table(name: 'city', indexes: [
    new ORM\Index(name: 'idx_name', columns: ['name']),
    new ORM\Index(name: 'idx_country', columns: ['country_id']),
    new ORM\Index(name: 'idx_instance', columns: ['instance_id'])
])]
class City
{
    use TimestampableEntity;

    #[ORM\Column(type: 'integer')]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[Groups([
        'administration',
        'administration_order_show'
    ])]
    private ?int $id = null;


    #[Assert\NotBlank()]
    #[ORM\Column(type: 'string', length: 255)]
    #[Groups([
        'administration',
        'administration_order_show'
    ])]
    private ?string $name = null;


    #[ORM\Column(name: 'postal_code', type: 'string', length: 255, nullable: true)]
    #[Groups([
        'administration'
    ])]
    private ?string $postalCode = null;


    #[ORM\OneToMany(targetEntity: Institution::class, mappedBy: 'city')]
    private $institutions;


    #[Assert\NotNull]
    #[ORM\ManyToOne(targetEntity: Country::class, inversedBy: 'cities')]
    #[ORM\JoinColumn(name: 'country_id', referencedColumnName: 'id', nullable: false)]
    private $country;


    #[Assert\NotNull]
    #[ORM\ManyToOne(targetEntity: Instance::class, inversedBy: 'cities')]
    #[ORM\JoinColumn(name: 'instance_id', referencedColumnName: 'id', nullable: false)]
    private $instance;


    public function __toString(): string
    { return $this->name; }

    public function __construct()
    {
        $this->institutions = new ArrayCollection();
    }

    public function getFullName(): string {
        return $this->getCountry()->getFullName() . ' - ' . $this->getName();
    }

    public function getId(): ?int
    { return $this->id; }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getName(): ?string
    { return $this->name; }

    public function setPostalCode(?string $postalCode): self
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    public function getPostalCode(): ?string
    { return $this->postalCode; }

    public function addInstitution(Institution $institution): void
    {
        $this->institutions[] = $institution;
    }

    public function removeInstitution(Institution $institution): void
    {
        $this->institutions->removeElement($institution);
    }

    public function getInstitutions(): Collection
    { return $this->institutions; }

    public function setCountry(Country $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getCountry(): Country
    { return $this->country; }

    public function setInstance(Instance $instance): self
    {
        $this->instance = $instance;

        return $this;
    }

    public function getInstance(): Instance
    { return $this->instance; }
}
