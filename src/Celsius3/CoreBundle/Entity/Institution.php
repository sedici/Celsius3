<?php

/*
 * Celsius3 - Order management
 * Copyright (C) 2014 PREBI-SEDICI <info@prebi.unlp.edu.ar> http://prebi.unlp.edu.ar http://sedici.unlp.edu.ar
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

namespace Celsius3\CoreBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Celsius3\CoreBundle\Repository\InstitutionRepository")
 */
class Institution extends Provider
{

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="string", length=255)
     */
    private $abbreviation;

    /**
     * @Assert\Url()
     * @ORM\Column(type="string", length=255)
     */
    private $website;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $address;

    /**
     * @ORM\OneToMany(targetEntity="BaseUser", mappedBy="institution")
     */
    private $users;

    /**
     * @ORM\OneToMany(targetEntity="Institution", mappedBy="parent")
     */
    private $institutions;

    /**
     * @ORM\ManyToOne(targetEntity="Institution", inversedBy="institutions")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     */
    private $parent;

    /**
     * @ORM\ManyToOne(targetEntity="City", inversedBy="institutions")
     * @ORM\JoinColumn(name="city_id", referencedColumnName="id")
     */
    private $city;

    /**
     * @Assert\NotNull
     * @ORM\ManyToOne(targetEntity="Country", inversedBy="institutions")
     * @ORM\JoinColumn(name="country_id", referencedColumnName="id")
     */
    private $country;

    /**
     * @ORM\OneToMany(targetEntity="Catalog", mappedBy="institution")
     */
    private $catalogs;

    /**
     * @ORM\OneToMany(targetEntity="Contact", mappedBy="institution")
     */
    private $contacts;

    /**
     * @Assert\NotNull
     * @ORM\ManyToOne(targetEntity="Instance", inversedBy="institutions")
     * @ORM\JoinColumn(name="instance_id", referencedColumnName="id")
     */
    private $instance;

    /**
     * @ORM\ManyToOne(targetEntity="LegacyInstance", inversedBy="ownerInstitutions" , cascade={"persist"})
     * @ORM\JoinColumn(name="celsius_instance_id", referencedColumnName="id")
     */
    private $celsiusInstance = null;

    /**
     * @ORM\ManyToOne(targetEntity="Hive", inversedBy="institutions")
     * @ORM\JoinColumn(name="hive_id", referencedColumnName="id")
     */
    private $hive;

    /**
     * @ORM\ManyToMany(targetEntity="BaseUser", mappedBy="librarianInstitution")
     */
    private $librarian;

    public function getProviderType()
    {
        return 'institution';
    }

    public function __toString()
    {
        return $this->abbreviation . ' - ' . $this->name;
    }

    public function getFullName()
    {
        return ($this->parent ? $this->parent->getFullName() . ' - ' : '') . $this->name;
    }

    public function __construct()
    {
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
        $this->institutions = new \Doctrine\Common\Collections\ArrayCollection();
        $this->catalogs = new \Doctrine\Common\Collections\ArrayCollection();
        $this->contacts = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set website
     *
     * @param  string $website
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
     * @param  string $address
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
     * @param Celsius3\CoreBundle\Entity\BaseUser $users
     */
    public function addUser(\Celsius3\CoreBundle\Entity\BaseUser $users)
    {
        $this->users[] = $users;
    }

    /**
     * Remove users
     *
     * @param Celsius3\CoreBundle\Entity\BaseUser $users
     */
    public function removeUser(\Celsius3\CoreBundle\Entity\BaseUser $users)
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
     * @param Celsius3\CoreBundle\Entity\Institution $institutions
     */
    public function addInstitution(
    \Celsius3\CoreBundle\Entity\Institution $institutions)
    {
        $this->institutions[] = $institutions;
    }

    /**
     * Remove institutions
     *
     * @param Celsius3\CoreBundle\Entity\Institution $institutions
     */
    public function removeInstitution(
    \Celsius3\CoreBundle\Entity\Institution $institutions)
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
     * @param  Celsius3\CoreBundle\Entity\Institution $parent
     * @return self
     */
    public function setParent(\Celsius3\CoreBundle\Entity\Institution $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return Celsius3\CoreBundle\Entity\Institution $parent
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set city
     *
     * @param  Celsius3\CoreBundle\Entity\City $city
     * @return self
     */
    public function setCity(\Celsius3\CoreBundle\Entity\City $city = null)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return Celsius3\CoreBundle\Entity\City $city
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set country
     *
     * @param  Celsius3\CoreBundle\Entity\Country $country
     * @return self
     */
    public function setCountry(\Celsius3\CoreBundle\Entity\Country $country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return Celsius3\CoreBundle\Entity\Country $country
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Add catalogs
     *
     * @param Celsius3\CoreBundle\Entity\Catalog $catalogs
     */
    public function addCatalog(\Celsius3\CoreBundle\Entity\Catalog $catalogs)
    {
        $this->catalogs[] = $catalogs;
    }

    /**
     * Remove catalogs
     *
     * @param Celsius3\CoreBundle\Entity\Catalog $catalogs
     */
    public function removeCatalog(
    \Celsius3\CoreBundle\Entity\Catalog $catalogs)
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
     * @param Celsius3\CoreBundle\Entity\Contact $contacts
     */
    public function addContact(\Celsius3\CoreBundle\Entity\Contact $contacts)
    {
        $this->contacts[] = $contacts;
    }

    /**
     * Remove contacts
     *
     * @param Celsius3\CoreBundle\Entity\Contact $contacts
     */
    public function removeContact(
    \Celsius3\CoreBundle\Entity\Contact $contacts)
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
     * @param  Celsius3\CoreBundle\Entity\Instance $instance
     * @return self
     */
    public function setInstance(\Celsius3\CoreBundle\Entity\Instance $instance)
    {
        $this->instance = $instance;

        return $this;
    }

    /**
     * Get instance
     *
     * @return Celsius3\CoreBundle\Entity\Instance $instance
     */
    public function getInstance()
    {
        return $this->instance;
    }

    /**
     * Set celsiusInstance
     *
     * @param  Celsius3\CoreBundle\Entity\LegacyInstance $celsiusInstance
     * @return self
     */
    public function setCelsiusInstance(\Celsius3\CoreBundle\Entity\LegacyInstance $celsiusInstance = null)
    {
        $this->celsiusInstance = $celsiusInstance;

        return $this;
    }

    /**
     * Get celsiusInstance
     *
     * @return Celsius3\CoreBundle\Entity\LegacyInstance $celsiusInstance
     */
    public function getCelsiusInstance()
    {
        return $this->celsiusInstance;
    }

    /**
     * Set hive
     *
     * @param  Celsius3\CoreBundle\Entity\Hive $hive
     * @return self
     */
    public function setHive(\Celsius3\CoreBundle\Entity\Hive $hive)
    {
        $this->hive = $hive;

        return $this;
    }

    /**
     * Get hive
     *
     * @return Celsius3\CoreBundle\Entity\Hive $hive
     */
    public function getHive()
    {
        return $this->hive;
    }

    /**
     * Add librarian
     *
     * @param \Celsius3\CoreBundle\Entity\BaseUser $librarian
     *
     * @return Institution
     */
    public function addLibrarian(\Celsius3\CoreBundle\Entity\BaseUser $librarian)
    {
        $this->librarian[] = $librarian;

        return $this;
    }

    /**
     * Remove librarian
     *
     * @param \Celsius3\CoreBundle\Entity\BaseUser $librarian
     */
    public function removeLibrarian(\Celsius3\CoreBundle\Entity\BaseUser $librarian)
    {
        $this->librarian->removeElement($librarian);
    }

    /**
     * Get librarian
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLibrarian()
    {
        return $this->librarian;
    }
}
