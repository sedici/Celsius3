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

namespace Celsius3\Entity;

use Celsius3\Validator\Constraints\ParentInstitution;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Celsius3\Repository\InstitutionRepository;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\ManyToMany;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Url;


#[ORM\Entity(repositoryClass: InstitutionRepository::class)]
class Institution extends Provider
{

    #[NotBlank]
    #[ORM\Column(type: 'string', length: 255)]
    #[Groups([
        "administration_list",
        "administration",
        "administration_order_show",
        "administration_user_show",
        "institution_show"
    ])]
    private string $name;


    #[NotBlank]
    #[ORM\Column(type: 'string', length: 255)]
    #[Groups([
        "administration_list",
        "administration",
        "administration_order_show",
        "administration_user_show",
        "institution_show"
    ])]
    private string $abbreviation;


    #[Url]
    #[ORM\Column(type: 'string', length: 255)]
    private string $website;


    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $address;


    #[OneToMany(targetEntity: BaseUser::class, mappedBy: 'institution')]
    private Collection $users;


    #[OneToMany(targetEntity: Institution::class, mappedBy: 'parent')]
    private Collection $institutions;


    #[ParentInstitution]
    #[ManyToOne(targetEntity: Institution::class, inversedBy: 'institutions')]
    #[JoinColumn(name: 'parent_id', referencedColumnName: 'id')]
    #[Groups([
        "administration_list",
        "administration",
        "administration_order_show",
        "administration_user_show",
        "institution_show"
    ])]
    private ?Institution $parent;


    #[ManyToOne(targetEntity: City::class, inversedBy: 'institutions')]
    #[JoinColumn(name: 'city_id', referencedColumnName: 'id')]
    #[Groups([
        "administration_order_show",
        "administration_user_show",
        "institution_show"
    ])]
    private ?City $city;


    #[NotNull]
    #[ManyToOne(targetEntity: Country::class, inversedBy: 'institutions')]
    #[JoinColumn(name: 'country_id', referencedColumnName: 'id')]
    #[Groups([
        "administration_user_show",
        "institution_show"
    ])]
    private Country $country;


    #[OneToMany(targetEntity: Catalog::class, mappedBy: 'institution')]
    private Collection $catalogs;


    #[OneToMany(targetEntity: Contact::class, mappedBy: 'institution')]
    private Collection $contacts;


    #[NotNull]
    #[ManyToOne(targetEntity: Instance::class, inversedBy: 'institutions')]
    #[JoinColumn(name: 'instance_id', referencedColumnName: 'id')]
    private Instance $instance;


    #[ManyToOne(targetEntity: LegacyInstance::class, inversedBy: 'ownerInstitutions', cascade: ['persist'])]
    #[JoinColumn(name: 'celsius_instance_id', referencedColumnName: 'id')]
    #[Groups([
        "administration_order_show",
        "administration_user_show",
        "institution_show"
    ])]
    private ?LegacyInstance $celsiusInstance = null;


    #[ManyToOne(targetEntity: Hive::class, inversedBy: 'institutions')]
    #[JoinColumn(name: 'hive_id', referencedColumnName: 'id')]
    private ?Hive $hive;


    #[ManyToMany(targetEntity: BaseUser::class, mappedBy: 'librarianInstitution')]
    private Collection $librarian;


    public function getProviderType()
    {
        return 'institution';
    }

    public function __toString(): string
    {
        return (string) $this->abbreviation.' - '.$this->name;
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
    public function getProviderName(): string
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

    public function findCelsiusInstance()
    {
        $institution = $this;
        do {
            $celsius_instance = $institution->getCelsiusInstance();
            $institution = $institution->getParent();
        } while ($institution && !$celsius_instance);

        return $celsius_instance && $celsius_instance->getEnabled() ? $celsius_instance : false;
    }
}
