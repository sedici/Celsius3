<?php

namespace Celsius3\CoreBundle\Document;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document
 */
class Institution extends Provider
{

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
     * @Assert\Url()
     * @MongoDB\String
     */
    private $website;

    /**
     * @MongoDB\String
     */
    private $address;

    /**
     * @Assert\NotNull()
     * @Assert\Type(type="boolean")
     * @MongoDB\Boolean
     */
    private $isLiblink = false;

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
     * @MongoDB\ReferenceMany(targetDocument="Contact", mappedBy="institution")
     */
    private $contacts;

    /**
     * @MongoDB\ReferenceOne(targetDocument="Instance", inversedBy="institutions")
     */
    private $instance;

    /**
     * @MongoDB\ReferenceOne(targetDocument="Instance", inversedBy="ownerInstitutions")
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
     * @return string
     */
    public function getProviderName()
    {
        return $this->__toString();
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
     * Set website
     *
     * @param string $website
     * @return self
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
     * @return self
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
     * @param Celsius3\CoreBundle\Document\BaseUser $users
     */
    public function addUser(\Celsius3\CoreBundle\Document\BaseUser $users)
    {
        $this->users[] = $users;
    }

    /**
     * Remove users
     *
     * @param Celsius3\CoreBundle\Document\BaseUser $users
     */
    public function removeUser(\Celsius3\CoreBundle\Document\BaseUser $users)
    {
        $this->users->removeElement($users);
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
     * @param Celsius3\CoreBundle\Document\Institution $institutions
     */
    public function addInstitution(
    \Celsius3\CoreBundle\Document\Institution $institutions)
    {
        $this->institutions[] = $institutions;
    }

    /**
     * Remove institutions
     *
     * @param Celsius3\CoreBundle\Document\Institution $institutions
     */
    public function removeInstitution(
    \Celsius3\CoreBundle\Document\Institution $institutions)
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
     * Set parent
     *
     * @param Celsius3\CoreBundle\Document\Institution $parent
     * @return self
     */
    public function setParent(
    \Celsius3\CoreBundle\Document\Institution $parent)
    {
        $this->parent = $parent;
        return $this;
    }

    /**
     * Get parent
     *
     * @return Celsius3\CoreBundle\Document\Institution $parent
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set city
     *
     * @param Celsius3\CoreBundle\Document\City $city
     * @return self
     */
    public function setCity(\Celsius3\CoreBundle\Document\City $city = null)
    {
        $this->city = $city;
        return $this;
    }

    /**
     * Get city
     *
     * @return Celsius3\CoreBundle\Document\City $city
     */
    public function getCity()
    {
        return $this->city;
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
     * Add catalogs
     *
     * @param Celsius3\CoreBundle\Document\Catalog $catalogs
     */
    public function addCatalog(\Celsius3\CoreBundle\Document\Catalog $catalogs)
    {
        $this->catalogs[] = $catalogs;
    }

    /**
     * Remove catalogs
     *
     * @param Celsius3\CoreBundle\Document\Catalog $catalogs
     */
    public function removeCatalog(
    \Celsius3\CoreBundle\Document\Catalog $catalogs)
    {
        $this->catalogs->removeElement($catalogs);
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
     * Add contacts
     *
     * @param Celsius3\CoreBundle\Document\Contact $contacts
     */
    public function addContact(\Celsius3\CoreBundle\Document\Contact $contacts)
    {
        $this->contacts[] = $contacts;
    }

    /**
     * Remove contacts
     *
     * @param Celsius3\CoreBundle\Document\Contact $contacts
     */
    public function removeContact(
    \Celsius3\CoreBundle\Document\Contact $contacts)
    {
        $this->contacts->removeElement($contacts);
    }

    /**
     * Get contacts
     *
     * @return Doctrine\Common\Collections\Collection $contacts
     */
    public function getContacts()
    {
        return $this->contacts;
    }

    /**
     * Set instance
     *
     * @param Celsius3\CoreBundle\Document\Instance $instance
     * @return self
     */
    public function setInstance(
    \Celsius3\CoreBundle\Document\Instance $instance = null)
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

    /**
     * Set celsiusInstance
     *
     * @param Celsius3\CoreBundle\Document\Instance $celsiusInstance
     * @return self
     */
    public function setCelsiusInstance(
    \Celsius3\CoreBundle\Document\Instance $celsiusInstance)
    {
        $this->celsiusInstance = $celsiusInstance;
        return $this;
    }

    /**
     * Get celsiusInstance
     *
     * @return Celsius3\CoreBundle\Document\Instance $celsiusInstance
     */
    public function getCelsiusInstance()
    {
        return $this->celsiusInstance;
    }

    /**
     * Set isLiblink
     *
     * @param boolean $isLiblink
     * @return self
     */
    public function setIsLiblink($isLiblink)
    {
        $this->isLiblink = $isLiblink;
        return $this;
    }

    /**
     * Get isLiblink
     *
     * @return boolean $isLiblink
     */
    public function getIsLiblink()
    {
        return is_null($this->parent) ? $this->isLiblink : $this->isLiblink || $this->getParent()->getIsLiblink();
    }

}
