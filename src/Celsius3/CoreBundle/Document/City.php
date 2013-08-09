<?php

namespace Celsius3\CoreBundle\Document;
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
     * @Assert\NotNull
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
     * Set postalCode
     *
     * @param string $postalCode
     * @return self
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
     * Set country
     *
     * @param Celsius3\CoreBundle\Document\Country $country
     * @return self
     */
    public function setCountry(\Celsius3\CoreBundle\Document\Country $country)
    {
        $this->country = $country;
        return $this;
    }

    /**
     * Get country
     *
     * @return Celsius3\CoreBundle\Document\Country $country
     */
    public function getCountry()
    {
        return $this->country;
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
