<?php

namespace Celsius\Celsius3Bundle\Document;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document
 */
class Journal
{
    /**
     * @MongoDB\Id
     */
    private $id;

    /**
     * @Assert\NotBlank()
     * @MongoDB\String
     */
    private $name;
    
    /**
     * @MongoDB\String
     */
    private $abbreviation;
    
    /**
     * @MongoDB\String
     */
    private $responsible;
    
    /**
     * @MongoDB\String
     */
    private $ISSN;
    
    /**
     * @MongoDB\String
     */
    private $ISSNE;
    
    /**
     * @MongoDB\String
     */
    private $frecuency;
    
    /**
     * @MongoDB\ReferenceMany(targetDocument="JournalType", mappedBy="journal")
     */
    private $materials;
    
    public function __construct()
    {
        $this->materials = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @param string $name
     * @return Journal
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
     * @param string $abbreviation
     * @return Journal
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
     * @param string $responsible
     * @return Journal
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
     * @param string $iSSN
     * @return Journal
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
     * @param string $iSSNE
     * @return Journal
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
     * @param string $frecuency
     * @return Journal
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
     * @param Celsius\Celsius3Bundle\Document\JournalType $materials
     */
    public function addMaterials(\Celsius\Celsius3Bundle\Document\JournalType $materials)
    {
        $this->materials[] = $materials;
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
}
