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

// use Celsius3\Entity\Mixin\TimestampableEntity;
use Celsius3\Repository\BaseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;


#[ORM\Entity(repositoryClass: BaseRepository::class)]
#[ORM\Table(
    name: "hive",
    indexes: [
        new ORM\Index(name: "idx_name", columns: ["name"])
    ]
)]
class Hive
{
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    // #[Groups([
    //     "administration_order_show",
    // ])]
    private ?int $id = null;


    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[ORM\Column(type: "string", length: 255)]
    // #[Groups([
    //     "administration_order_show",
    // ])]
    private string $name;


    #[ORM\OneToMany(targetEntity: LegacyInstance::class, mappedBy: "hive")]
    private Collection $instances;


    #[ORM\OneToMany(targetEntity: Institution::class, mappedBy: "hive")]
    private Collection $institutions;


    public function __toString(): string
    {
        return $this->name;
    }

    public function __construct()
    {
        $this->instances = new ArrayCollection();
        $this->institutions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function addInstance(LegacyInstance $instance): void
    {
        $this->instances[] = $instance;
    }

    public function removeInstance(LegacyInstance $instance): void
    {
        $this->instances->removeElement($instance);
    }

    public function getInstances(): array|ArrayCollection
    {
        return $this->instances;
    }

    public function addInstitution(Institution $institution): void
    {
        $this->institutions[] = $institution;
    }

    public function removeInstitution(Institution $institution): void
    {
        $this->institutions->removeElement($institution);
    }

    public function getInstitutions(): array|ArrayCollection
    {
        return $this->institutions;
    }
}
