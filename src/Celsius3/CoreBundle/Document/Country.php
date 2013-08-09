<?php

namespace Celsius3\CoreBundle\Document;
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
     * @MongoDB\ReferenceMany(targetDocument="Institution", mappedBy="country")
     */
    private $institutions;

    /**
     * @Assert\NotNull
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
     * @param string $abbreviation
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
     * Add cities
     *
     * @param Celsius3\CoreBundle\Document\City $cities
     */
    public function addCitie(\Celsius3\CoreBundle\Document\City $cities)
    {
        $this->cities[] = $cities;
    }

    /**
     * Remove cities
     *
     * @param Celsius3\CoreBundle\Document\City $cities
     */
    public function removeCitie(\Celsius3\CoreBundle\Document\City $cities)
    {
        $this->cities->removeElement($cities);
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
     * Add institutions
     *
     * @param Celsius3\CoreBundle\Document\Institution $institutions
     */
    public function addInstitution(\Celsius3\CoreBundle\Document\Institution $institutions)
    {
        $this->institutions[] = $institutions;
    }

    /**
     * Remove institutions
     *
     * @param Celsius3\CoreBundle\Document\Institution $institutions
     */
    public function removeInstitution(\Celsius3\CoreBundle\Document\Institution $institutions)
    {
        $this->institutions->removeElement($institutions);
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
     * Set instance
     *
     * @param Celsius3\CoreBundle\Document\Instance $instance
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
