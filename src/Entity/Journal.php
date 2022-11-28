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
 * @ORM\Entity(repositoryClass="Celsius3\Repository\JournalRepository")
 * @ORM\Table(name="journal", indexes={
 *   @ORM\Index(name="idx_name", columns={"name"}),
 *   @ORM\Index(name="idx_abbreviation", columns={"abbreviation"}),
 *   @ORM\Index(name="idx_instance", columns={"instance_id"})
 * })
 */
class Journal
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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $abbreviation;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $responsible;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $ISSN;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $ISSNE;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $frecuency;
    /**
     * @ORM\OneToMany(targetEntity="JournalType", mappedBy="journal")
     */
    private $materials;
    /**
     * @Assert\NotNull
     * @ORM\ManyToOne(targetEntity="Instance")
     * @ORM\JoinColumn(name="instance_id", referencedColumnName="id", nullable=false)
     */
    private $instance;

    public function __construct()
    {
        $this->materials = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->name;
    }

    public function asJson()
    {
        $value = $this->name;
        if($this->getISSN()){
            $value = $value.' - (ISSN: '.$this->ISSN.')'; 
        }
        if($this->getResponsible()){
        $value = $value.' - (Responsable: '. $this->getResponsible().')';
        }
        return  array(
            'id' => $this->getId(),
            'value' => $value
        );
        
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
     * Get name.
     *
     * @return string $name
     */
    public function getFullName()
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
     * Set responsible.
     *
     * @param string $responsible
     *
     * @return self
     */
    public function setResponsible($responsible)
    {
        $this->responsible = $responsible;

        return $this;
    }

    /**
     * Get responsible.
     *
     * @return string $responsible
     */
    public function getResponsible()
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
    public function setISSN($iSSN)
    {
        $this->ISSN = $iSSN;

        return $this;
    }

    /**
     * Get ISSN.
     *
     * @return string $iSSN
     */
    public function getISSN()
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
    public function setISSNE($iSSNE)
    {
        $this->ISSNE = $iSSNE;

        return $this;
    }

    /**
     * Get ISSNE.
     *
     * @return string $iSSNE
     */
    public function getISSNE()
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
    public function setFrecuency($frecuency)
    {
        $this->frecuency = $frecuency;

        return $this;
    }

    /**
     * Get frecuency.
     *
     * @return string $frecuency
     */
    public function getFrecuency()
    {
        return $this->frecuency;
    }

    /**
     * Add materials.
     *
     * @param JournalType $materials
     */
    public function addMaterial(JournalType $materials)
    {
        $this->materials[] = $materials;
    }

    /**
     * Remove materials.
     *
     * @param JournalType $materials
     */
    public function removeMaterial(JournalType $materials)
    {
        $this->materials->removeElement($materials);
    }

    /**
     * Get materials.
     *
     * @return Collection $materials
     */
    public function getMaterials()
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
}
