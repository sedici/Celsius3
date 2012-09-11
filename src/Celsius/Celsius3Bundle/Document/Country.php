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
    protected $id;

    /**
     * @Assert\NotBlank()
     * @MongoDB\String
     * @MongoDB\UniqueIndex(order="asc")
     */
    protected $name;

    /**
     * @MongoDB\ReferenceMany(targetDocument="City", mappedBy="country")
     */
    protected $cities;
    
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

}
