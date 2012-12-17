<?php

namespace Celsius\Celsius3Bundle\Document;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document
 */
class City
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
    private $postalCode;

    /**
     * @MongoDB\ReferenceMany(targetDocument="Institution", mappedBy="city")
     */
    private $institutions;

    /**
     * @MongoDB\ReferenceOne(targetDocument="Country", inversedBy="cities") 
     */
    private $country;

    /**
     * @MongoDB\ReferenceOne(targetDocument="Instance", inversedBy="cities") 
     */
    private $instance;

    public function __toString()
    {
        return $this->name;
    }

    public function __construct()
    {
        $this->institutions = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Location
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
     * Set postalCode
     *
     * @param string $postalCode
     * @return Location
     */
    public function setPostalCode($postalCode)
    {
        $this->postalCode = $postalCode;
        return $this;
    }

    /**
     * Get postalCode
     *
     * @return string $postalCode
     */
    public function getPostalCode()
    {
        return $this->postalCode;
    }

    /**
     * Add institutions
     *
     * @param Celsius\Celsius3Bundle\Document\Institution $institutions
     */
    public function addInstitutions(\Celsius\Celsius3Bundle\Document\Institution $institutions)
    {
        $this->institutions[] = $institutions;
    }

    /**
     * Get institutions
     *
     * @return Doctrine\Common\Collections\Collection $institutions
     */
    public function getInstitutions()
    {
        return $this->institutions;
    }

    /**
     * Set country
     *
     * @param Celsius\Celsius3Bundle\Document\Country $country
     * @return Location
     */
    public function setCountry(\Celsius\Celsius3Bundle\Document\Country $country)
    {
        $this->country = $country;
        return $this;
    }

    /**
     * Get country
     *
     * @return Celsius\Celsius3Bundle\Document\Country $country
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set instance
     *
     * @param Celsius\Celsius3Bundle\Document\Instance $instance
     * @return City
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

}
