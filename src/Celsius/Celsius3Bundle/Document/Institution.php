<?php

namespace Celsius\Celsius3Bundle\Document;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document
 */
class Institution
{

    /**
     * @MongoDB\Id
     */
    protected $id;

    /**
     * @Assert\NotBlank()
     * @MongoDB\String
     */
    protected $name;

    /**
     * @Assert\NotBlank()
     * @MongoDB\String
     */
    protected $abbreviation;

    /**
     * @Assert\NotBlank()
     * @MongoDB\String
     */
    protected $website;

    /**
     * @Assert\NotBlank()
     * @MongoDB\String
     */
    protected $address;

    /**
     * @MongoDB\ReferenceMany(targetDocument="BaseUser", mappedBy="institution")
     */
    protected $users;

    /**
     * @MongoDB\ReferenceMany(targetDocument="Institution", mappedBy="parent")
     */
    protected $institutions;

    /**
     * @MongoDB\ReferenceOne(targetDocument="Institution", inversedBy="institutions") 
     */
    protected $parent;

    /**
     * @MongoDB\ReferenceOne(targetDocument="City", inversedBy="institutions") 
     */
    protected $city;
    
    /**
     * @MongoDB\ReferenceOne(targetDocument="Catalog", mappedBy="institution") 
     */
    protected $catalog;
    
    /**
     * @MongoDB\ReferenceOne(targetDocument="Instance", inversedBy="institutions")
     */
    protected $instance;

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
    public function setInstance(\Celsius\Celsius3Bundle\Document\Instance $instance)
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
     * Set catalog
     *
     * @param Celsius\Celsius3Bundle\Document\Catalog $catalog
     * @return Institution
     */
    public function setCatalog(\Celsius\Celsius3Bundle\Document\Catalog $catalog)
    {
        $this->catalog = $catalog;
        return $this;
    }

    /**
     * Get catalog
     *
     * @return Celsius\Celsius3Bundle\Document\Catalog $catalog
     */
    public function getCatalog()
    {
        return $this->catalog;
    }
}
