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

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

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
        return $this->abbreviation.' - '.$this->name;
    }

    public function getFullName($ids = [])
    {
        $ids[] = $this->getId();
        $parent = $this->getParent();
        return ($parent && !in_array($parent->getId(), $ids) ? $parent->getFullName().' - ' : '').$this->name;
    }

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->institutions = new ArrayCollection();
        $this->catalogs = new ArrayCollection();
        $this->contacts = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getProviderName()
    {
        return $this->__toString();
    }

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string $name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set abbreviation.
     *
     * @param string $abbreviation
     *
     * @return self
     */
    public function setAbbreviation($abbreviation)
    {
        $this->abbreviation = $abbreviation;

        return $this;
    }

    /**
     * Get abbreviation.
     *
     * @return string $abbreviation
     */
    public function getAbbreviation()
    {
        return $this->abbreviation;
    }

    /**
     * Set website.
     *
     * @param string $website
     *
     * @return self
     */
    public function setWebsite($website)
    {
        $this->website = $website;

        return $this;
    }

    /**
     * Get website.
     *
     * @return string $website
     */
    public function getWebsite()
    {
        return $this->website;
    }

    /**
     * Set address.
     *
     * @param string $address
     *
     * @return self
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address.
     *
     * @return string $address
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Add users.
     *
     * @param BaseUser $users
     */
    public function addUser(BaseUser $users)
    {
        $this->users[] = $users;
    }

    /**
     * Remove users.
     *
     * @param BaseUser $users
     */
    public function removeUser(BaseUser $users)
    {
        $this->users->removeElement($users);
    }

    /**
     * Get users.
     *
     * @return Collection $users
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * Add institution.
     *
     * @param Institution $institution
     */
    public function addInstitution(Institution $institution)
    {
        $this->institutions[] = $institution;
    }

    /**
     * Remove institutions.
     *
     * @param Institution $institution
     */
    public function removeInstitution(Institution $institution)
    {
        $this->institutions->removeElement($institution);
    }

    /**
     * Get institutions.
     *
     * @return Collection $institutions
     */
    public function getInstitutions()
    {
        return $this->institutions;
    }

    /**
     * Set parent.
     *
     * @param Institution $parent
     *
     * @return self
     */
    public function setParent(Institution $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent.
     *
     * @return Institution $parent
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set city.
     *
     * @param City $city
     *
     * @return self
     */
    public function setCity(City $city = null)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city.
     *
     * @return City $city
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set country.
     *
     * @param Country $country
     *
     * @return self
     */
    public function setCountry(Country $country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country.
     *
     * @return Country $country
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Add catalogs.
     *
     * @param Catalog $catalogs
     */
    public function addCatalog(Catalog $catalogs)
    {
        $this->catalogs[] = $catalogs;
    }

    /**
     * Remove catalogs.
     *
     * @param Catalog $catalogs
     */
    public function removeCatalog(Catalog $catalogs)
    {
        $this->catalogs->removeElement($catalogs);
    }

    /**
     * Get catalogs.
     *
     * @return Collection $catalogs
     */
    public function getCatalogs()
    {
        return $this->catalogs;
    }

    /**
     * Add contacts.
     *
     * @param Contact $contacts
     */
    public function addContact(Contact $contacts)
    {
        $this->contacts[] = $contacts;
    }

    /**
     * Remove contacts.
     *
     * @param Contact $contacts
     */
    public function removeContact(Contact $contacts)
    {
        $this->contacts->removeElement($contacts);
    }

    /**
     * Get contacts.
     *
     * @return Collection $contacts
     */
    public function getContacts()
    {
        return $this->contacts;
    }

    /**
     * Set instance.
     *
     * @param Instance $instance
     *
     * @return self
     */
    public function setInstance(Instance $instance)
    {
        $this->instance = $instance;

        return $this;
    }

    /**
     * Get instance.
     *
     * @return Instance $instance
     */
    public function getInstance()
    {
        return $this->instance;
    }

    /**
     * Set celsiusInstance.
     *
     * @param LegacyInstance $celsiusInstance
     *
     * @return self
     */
    public function setCelsiusInstance(LegacyInstance $celsiusInstance = null)
    {
        $this->celsiusInstance = $celsiusInstance;

        return $this;
    }

    /**
     * Get celsiusInstance.
     *
     * @return LegacyInstance $celsiusInstance
     */
    public function getCelsiusInstance()
    {
        return $this->celsiusInstance;
    }

    /**
     * Set hive.
     *
     * @param Hive $hive
     *
     * @return self
     */
    public function setHive(Hive $hive)
    {
        $this->hive = $hive;

        return $this;
    }

    /**
     * Get hive.
     *
     * @return Hive $hive
     */
    public function getHive()
    {
        return $this->hive;
    }

    /**
     * Add librarian.
     *
     * @param BaseUser $librarian
     *
     * @return Institution
     */
    public function addLibrarian(BaseUser $librarian)
    {
        $this->librarian[] = $librarian;

        return $this;
    }

    /**
     * Remove librarian.
     *
     * @param BaseUser $librarian
     */
    public function removeLibrarian(BaseUser $librarian)
    {
        $this->librarian->removeElement($librarian);
    }

    /**
     * Get librarian.
     *
     * @return Collection
     */
    public function getLibrarian()
    {
        return $this->librarian;
    }
}
