<?php

namespace Celsius\Celsius3Bundle\Document;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document
 */
class Institution extends Provider
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
     * @Assert\NotBlank()
     * @MongoDB\String
     */
    private $abbreviation;

    /**
     * @Assert\NotBlank()
     * @Assert\Url()
     * @MongoDB\String
     */
    private $website;

    /**
     * @Assert\NotBlank()
     * @MongoDB\String
     */
    private $address;

    /**
     * @MongoDB\ReferenceMany(targetDocument="BaseUser", mappedBy="institution")
     */
    private $users;

    /**
     * @MongoDB\ReferenceMany(targetDocument="Institution", mappedBy="parent")
     */
    private $institutions;

    /**
     * @MongoDB\ReferenceOne(targetDocument="Institution", inversedBy="institutions") 
     */
    private $parent;

    /**
     * @MongoDB\ReferenceOne(targetDocument="City", inversedBy="institutions") 
     */
    private $city;

    /**
     * @MongoDB\ReferenceOne(targetDocument="Country", inversedBy="institutions") 
     */
    private $country;

    /**
     * @MongoDB\ReferenceMany(targetDocument="Catalog", mappedBy="institution") 
     */
    private $catalogs;

    /**
     * @MongoDB\ReferenceOne(targetDocument="Instance", inversedBy="institutions")
     */
    private $instance;

    /**
     * @MongoDB\ReferenceOne(targetDocument="Instance", inversedBy="institutions") 
     */
    private $celsiusInstance;

    public function __toString()
    {
        return $this->abbreviation . ' - ' . $this->name;
    }

    public function __construct()
    {
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Institution
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
     * @return Institution
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
     * Set website
     *
     * @param string $website
     * @return Institution
     */
    public function setWebsite($website)
    {
        $this->website = $website;
        return $this;
    }

    /**
     * Get website
     *
     * @return string $website
     */
    public function getWebsite()
    {
        return $this->website;
    }

    /**
     * Set address
     *
     * @param string $address
     * @return Institution
     */
    public function setAddress($address)
    {
        $this->address = $address;
        return $this;
    }

    /**
     * Get address
     *
     * @return string $address
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Add users
     *
     * @param Celsius\Celsius3Bundle\Document\BaseUser $users
     */
    public function addUsers(\Celsius\Celsius3Bundle\Document\BaseUser $users)
    {
        $this->users[] = $users;
    }

    /**
     * Get users
     *
     * @return Doctrine\Common\Collections\Collection $users
     */
    public function getUsers()
    {
        return $this->users;
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
     * Set parent
     *
     * @param Celsius\Celsius3Bundle\Document\Institution $parent
     * @return Institution
     */
    public function setParent(\Celsius\Celsius3Bundle\Document\Institution $parent)
    {
        $this->parent = $parent;
        return $this;
    }

    /**
     * Get parent
     *
     * @return Celsius\Celsius3Bundle\Document\Institution $parent
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set location
     *
     * @param Celsius\Celsius3Bundle\Document\City $city
     * @return Institution
     */
    public function setCity(\Celsius\Celsius3Bundle\Document\City $city)
    {
        $this->city = $city;
        return $this;
    }

    /**
     * Get location
     *
     * @return Celsius\Celsius3Bundle\Document\City $city
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set instance
     *
     * @param Celsius\Celsius3Bundle\Document\Instance $instance
     * @return Institution
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
     * Set celsiusInstance
     *
     * @param Celsius\Celsius3Bundle\Document\Instance $celsiusInstance
     * @return Institution
     */
    public function setCelsiusInstance(\Celsius\Celsius3Bundle\Document\Instance $celsiusInstance = null)
    {
        $this->celsiusInstance = $celsiusInstance;
        return $this;
    }

    /**
     * Get celsiusInstance
     *
     * @return Celsius\Celsius3Bundle\Document\Instance $celsiusInstance
     */
    public function getCelsiusInstance()
    {
        return $this->celsiusInstance;
    }

    /**
     * Add catalogs
     *
     * @param Celsius\Celsius3Bundle\Document\Catalog $catalogs
     */
    public function addCatalogs(\Celsius\Celsius3Bundle\Document\Catalog $catalogs)
    {
        $this->catalogs[] = $catalogs;
    }

    /**
     * Get catalogs
     *
     * @return Doctrine\Common\Collections\Collection $catalogs
     */
    public function getCatalogs()
    {
        return $this->catalogs;
    }

    /**
     * Set country
     *
     * @param Celsius\Celsius3Bundle\Document\Country $country
     * @return \Institution
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

    public function getProviderName()
    {
        return $this->getName();
    }

}
