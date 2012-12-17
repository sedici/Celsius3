<?php

namespace Celsius\Celsius3Bundle\Document;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document
 */
class Country
{

    /**
     * @MongoDB\Id
     */
    private $id;

    /**
     * @Assert\NotBlank()
     * @MongoDB\String
     * @MongoDB\UniqueIndex(order="asc")
     */
    private $name;

    /**
     * @Assert\NotBlank()
     * @MongoDB\String
     * @MongoDB\UniqueIndex(order="asc")
     */
    private $abbreviation;

    /**
     * @MongoDB\ReferenceMany(targetDocument="City", mappedBy="country")
     */
    private $cities;

    /**
     * @MongoDB\ReferenceOne(targetDocument="Instance", inversedBy="countries") 
     */
    private $instance;

    public function __toString()
    {
        return $this->name;
    }

    public function __construct()
    {
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
     * @param string $name
     * @return Country
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
     * Add cities
     *
     * @param Celsius\Celsius3Bundle\Document\City $cities
     */
    public function addCities(\Celsius\Celsius3Bundle\Document\City $cities)
    {
        $this->cities[] = $cities;
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
     * Set instance
     *
     * @param Celsius\Celsius3Bundle\Document\Instance $instance
     * @return Country
     */
    public function setInstance(\Celsius\Celsius3Bundle\Document\Instance $instance = null)
    {
        $this->instance = $instance;
        return $this;
    }

    /**
     * Get instance
     *
     * @return Celsius\Celsius3Bundle\Document\Instance $instance
     */
    public function getInstance()
    {
        return $this->instance;
    }

    /**
     * Set abbreviation
     *
     * @param string $abbreviation
     * @return \Country
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

}
