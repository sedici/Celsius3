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

namespace Celsius3\CoreBundle\Document;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Gedmo\Timestampable\Traits\TimestampableDocument;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ODM\Document
 * @ODM\Indexes({
 *   @ODM\Index(keys={"instance.id"="asc", "name"="asc"}),
 *   @ODM\Index(keys={"name"="asc"}),
 * })
 */
class Journal
{
    use TimestampableDocument;
    /**
     * @ODM\Id
     */
    private $id;
    /**
     * @Assert\NotBlank()
     * @ODM\String
     */
    private $name;
    /**
     * @ODM\String
     */
    private $abbreviation;
    /**
     * @ODM\String
     */
    private $responsible;
    /**
     * @ODM\String
     */
    private $ISSN;
    /**
     * @ODM\String
     */
    private $ISSNE;
    /**
     * @ODM\String
     */
    private $frecuency;
    /**
     * @ODM\ReferenceMany(targetDocument="JournalType", mappedBy="journal")
     */
    private $materials;
    /**
     * @Assert\NotNull
     * @ODM\ReferenceOne(targetDocument="Instance")
     */
    private $instance;

    public function __construct()
    {
        $this->materials = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function __toString()
    {
        return $this->name;
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
     * Set responsible
     *
     * @param  string $responsible
     * @return self
     */
    public function setResponsible($responsible)
    {
        $this->responsible = $responsible;

        return $this;
    }

    /**
     * Get responsible
     *
     * @return string $responsible
     */
    public function getResponsible()
    {
        return $this->responsible;
    }

    /**
     * Set ISSN
     *
     * @param  string $iSSN
     * @return self
     */
    public function setISSN($iSSN)
    {
        $this->ISSN = $iSSN;

        return $this;
    }

    /**
     * Get ISSN
     *
     * @return string $iSSN
     */
    public function getISSN()
    {
        return $this->ISSN;
    }

    /**
     * Set ISSNE
     *
     * @param  string $iSSNE
     * @return self
     */
    public function setISSNE($iSSNE)
    {
        $this->ISSNE = $iSSNE;

        return $this;
    }

    /**
     * Get ISSNE
     *
     * @return string $iSSNE
     */
    public function getISSNE()
    {
        return $this->ISSNE;
    }

    /**
     * Set frecuency
     *
     * @param  string $frecuency
     * @return self
     */
    public function setFrecuency($frecuency)
    {
        $this->frecuency = $frecuency;

        return $this;
    }

    /**
     * Get frecuency
     *
     * @return string $frecuency
     */
    public function getFrecuency()
    {
        return $this->frecuency;
    }

    /**
     * Add materials
     *
     * @param Celsius3\CoreBundle\Document\JournalType $materials
     */
    public function addMaterial(\Celsius3\CoreBundle\Document\JournalType $materials)
    {
        $this->materials[] = $materials;
    }

    /**
     * Remove materials
     *
     * @param Celsius3\CoreBundle\Document\JournalType $materials
     */
    public function removeMaterial(\Celsius3\CoreBundle\Document\JournalType $materials)
    {
        $this->materials->removeElement($materials);
    }

    /**
     * Get materials
     *
     * @return Doctrine\Common\Collections\Collection $materials
     */
    public function getMaterials()
    {
        return $this->materials;
    }

    /**
     * Set instance
     *
     * @param  Celsius3\CoreBundle\Document\Instance $instance
     * @return self
     */
    public function setInstance(\Celsius3\CoreBundle\Document\Instance $instance)
    {
        $this->instance = $instance;

        return $this;
    }

    /**
     * Get instance
     *
     * @return Celsius3\CoreBundle\Document\Instance $instance
     */
    public function getInstance()
    {
        return $this->instance;
    }
}