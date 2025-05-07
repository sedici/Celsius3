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
use Celsius3\Repository\JournalRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;


#[ORM\Entity(repositoryClass: JournalRepository::class)]
#[ORM\Table(name: "journal", indexes: [
    new ORM\Index(name: "idx_name", columns: ["name"]),
    new ORM\Index(name: "idx_abbreviation", columns: ["abbreviation"]),
    new ORM\Index(name: "idx_instance", columns: ["instance_id"])
])]
class Journal
{
    use TimestampableEntity;

    #[ORM\Column(type: "integer")]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    #[Groups([
        "administration_list",
        "administration_order_show",
        "administration_user_show",
        "user_list"
    ])]
    private ?int $id = null;


    #[Assert\NotBlank]
    #[ORM\Column(type: "string", length: 255)]
    #[Groups([
        "administration_list",
        "administration_order_show",
        "administration_user_show",
        "user_list",
        "ajax_list_name"
    ])]
    private ?string $name;


    #[ORM\Column(type: "string", length: 255, nullable: true)]
    #[Groups([
        "administration_list",
        "administration_order_show",
        "administration_user_show",
        "user_list",
        "ajax_list"
    ])]
    private ?string $abbreviation;


    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $responsible;


    #[ORM\Column(type: "string", length: 255, nullable: true)]
    #[Groups([
        "administration_order_show",
        "ajax_list"
    ])]
    private ?string $ISSN;


    #[ORM\Column(type: "string", length: 255, nullable: true)]
    #[Groups(["ajax_list"])]
    private ?string $ISSNE;


    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $frecuency;
    #[ORM\OneToMany(targetEntity: JournalType::class, mappedBy: "journal", fetch: "EXTRA_LAZY")]
    private Collection $materials;


    #[Assert\NotNull]
    #[ORM\ManyToOne(targetEntity: Instance::class)]
    #[ORM\JoinColumn(name: "instance_id", referencedColumnName: "id", nullable: false)]
    private ?Instance $instance;


    public function __construct() {
        $this->materials = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->name;
    }

    /**
     * Get id.
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
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string $name
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Get name.
     *
     * @return string $name
     */
    public function getFullName(): string
    {
        return $this->name.' - (Instance: '.$this->getInstance()->getUrl().') - (ISSN: '.$this->ISSN.')';
    }

    /**
     * Set abbreviation.
     *
     * @param string $abbreviation
     *
     * @return self
     */
    public function setAbbreviation(string $abbreviation): self
    {
        $this->abbreviation = $abbreviation;

        return $this;
    }

    /**
     * Get abbreviation.
     *
     * @return string $abbreviation
     */
    public function getAbbreviation(): ?string
    {
        return $this->abbreviation;
    }

    /**
     * Set responsible.
     *
     * @param string $responsible
     *
     * @return self
     */
    public function setResponsible(string $responsible): self
    {
        $this->responsible = $responsible;

        return $this;
    }

    /**
     * Get responsible.
     *
     * @return string $responsible
     */
    public function getResponsible(): ?string
    {
        return $this->responsible;
    }

    /**
     * Set ISSN.
     *
     * @param string $iSSN
     *
     * @return self
     */
    public function setISSN(string $iSSN): self
    {
        $this->ISSN = $iSSN;

        return $this;
    }

    /**
     * Get ISSN.
     *
     * @return string $iSSN
     */
    public function getISSN(): ?string
    {
        return $this->ISSN;
    }

    /**
     * Set ISSNE.
     *
     * @param string $iSSNE
     *
     * @return self
     */
    public function setISSNE(string $iSSNE): self
    {
        $this->ISSNE = $iSSNE;

        return $this;
    }

    /**
     * Get ISSNE.
     *
     * @return string $iSSNE
     */
    public function getISSNE(): ?string
    {
        return $this->ISSNE;
    }

    /**
     * Set frecuency.
     *
     * @param string $frecuency
     *
     * @return self
     */
    public function setFrecuency(string $frecuency): self
    {
        $this->frecuency = $frecuency;

        return $this;
    }

    /**
     * Get frecuency.
     *
     * @return string $frecuency
     */
    public function getFrecuency(): ?string
    {
        return $this->frecuency;
    }

    /**
     * Add materials.
     *
     * @param JournalType $materials
     */
    public function addMaterial(JournalType $materials): void
    {
        $this->materials[] = $materials;
    }

    /**
     * Remove materials.
     *
     * @param JournalType $materials
     */
    public function removeMaterial(JournalType $materials): void
    {
        $this->materials->removeElement($materials);
    }

    /**
     * Get materials.
     *
     * @return Collection $materials
     */
    public function getMaterials(): Collection
    {
        return $this->materials;
    }

    /**
     * Set instance.
     *
     * @param Instance $instance
     *
     * @return self
     */
    public function setInstance(Instance $instance): self
    {
        $this->instance = $instance;

        return $this;
    }

    /**
     * Get instance.
     *
     * @return Instance $instance
     */
    public function getInstance(): ?Instance
    {
        return $this->instance;
    }
}
