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

use Celsius3\Entity\Mixin\TimestampableEntity;
use Celsius3\Repository\LegacyInstanceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;


#[ORM\Entity(repositoryClass: LegacyInstanceRepository::class)]
#[ORM\Table(name: "instance", indexes: [
    new ORM\Index(name: "idx_name", columns: ["name"]),
    new ORM\Index(name: "idx_website", columns: ["website"]),
    new ORM\Index(name: "idx_hive", columns: ["hive_id"]),
    new ORM\Index(name: "idx_url", columns: ["url"]),
    new ORM\Index(name: "idx_type", columns: ["type"])
])]
#[ORM\InheritanceType("SINGLE_TABLE")]
#[ORM\DiscriminatorColumn(name: "type", type: "string")]
#[ORM\DiscriminatorMap([
    "legacy" => LegacyInstance::class,
    "current" => Instance::class
])]
#[UniqueEntity("email")]
#[ORM\HasLifecycleCallbacks]
class LegacyInstance
{
    use TimestampableEntity;

    #[ORM\Column(type: "integer")]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    #[Groups(["administration_order_show"])]
    protected ?int $id = null;


    #[Assert\NotBlank]
    #[ORM\Column(type: "string", length: 255)]
    #[Groups(["administration_order_show", "email_template"])]
    protected ?string $name;


    #[Assert\NotBlank]
    #[ORM\Column(type: "string", length: 255)]
    #[Groups(["administration_order_show", "email_template"])]
    protected ?string $abbreviation;


    #[Assert\NotBlank]
    #[Assert\Url]
    #[ORM\Column(type: "string", length: 255)]
    #[Groups(["email_template"])]
    protected ?string $website;


    #[Assert\NotBlank]
    #[Assert\Email]
    #[ORM\Column(type: "string", length: 255)]
    #[Groups(["email_template"])]
    protected ?string $email;


    #[Assert\Type(type: "boolean")]
    #[ORM\Column(type: "boolean")]
    #[Groups(["administration_order_show"])]
    protected bool $enabled = true;


    #[ORM\ManyToOne(targetEntity: Hive::class, inversedBy: "instances")]
    #[ORM\JoinColumn(name: "hive_id", referencedColumnName: "id")]
    #[Groups(["administration_order_show"])]
    protected ?Hive $hive;


    #[ORM\OneToMany(targetEntity: Institution::class, mappedBy: "celsiusInstance", fetch: "EXTRA_LAZY")]
    protected Collection $ownerInstitutions;


    public function __construct()
    {
        $this->ownerInstitutions = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->getName();
    }

    public function isCurrent(): bool
    {
        return false;
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setAbbreviation(string $abbreviation): self
    {
        $this->abbreviation = $abbreviation;

        return $this;
    }

    public function getAbbreviation(): ?string
    {
        return $this->abbreviation;
    }

    public function setWebsite(string $website): self
    {
        $this->website = $website;

        return $this;
    }

    public function getWebsite(): ?string
    {
        return $this->website;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;

        return $this;
    }

    public function getEnabled(): bool
    {
        return $this->enabled;
    }

    public function setHive(Hive $hive): self
    {
        $this->hive = $hive;

        return $this;
    }

    public function getHive(): ?Hive
    {
        return $this->hive;
    }

    public function addOwnerInstitution(Institution $ownerInstitutions): void
    {
        $this->ownerInstitutions[] = $ownerInstitutions;
    }

    public function removeOwnerInstitution(Institution $ownerInstitutions): void
    {
        $this->ownerInstitutions->removeElement($ownerInstitutions);
    }

    public function getOwnerInstitutions(): Collection
    {
        return $this->ownerInstitutions;
    }
}
