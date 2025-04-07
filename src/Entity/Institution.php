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


    #[OneToMany(targetEntity: BaseUser::class, mappedBy: 'institution', fetch: "EXTRA_LAZY")]
    private Collection $users;


    #[OneToMany(targetEntity: Institution::class, mappedBy: 'parent', fetch: "EXTRA_LAZY")]
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


    #[OneToMany(targetEntity: Catalog::class, mappedBy: 'institution', fetch: "EXTRA_LAZY")]
    private Collection $catalogs;


    #[OneToMany(targetEntity: Contact::class, mappedBy: 'institution', fetch: "EXTRA_LAZY")]
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


    public function getProviderType(): string
    { return 'institution'; }

    public function __toString(): string
    { return (string) $this->abbreviation.' - '.$this->name; }

    public function getFullName(array $ids = []): string
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

    public function getProviderName(): string
    {
        return $this->__toString();
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setAbbreviation(string $abbreviation): self
    {
        $this->abbreviation = $abbreviation;

        return $this;
    }

    public function getAbbreviation(): string
    {
        return $this->abbreviation;
    }

    public function setWebsite(string $website): self
    {
        $this->website = $website;

        return $this;
    }

    public function getWebsite(): string
    {
        return $this->website;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function addUser(BaseUser $users): void
    {
        $this->users[] = $users;
    }

    public function removeUser(BaseUser $users): void
    {
        $this->users->removeElement($users);
    }

    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addInstitution(Institution $institution): void
    {
        $this->institutions[] = $institution;
    }

    public function removeInstitution(Institution $institution): void
    {
        $this->institutions->removeElement($institution);
    }

    public function getInstitutions(): Collection
    {
        return $this->institutions;
    }

    public function setParent(?Institution $parent = null): self
    {
        $this->parent = $parent;

        return $this;
    }

    public function getParent(): ?Institution
    {
        return $this->parent;
    }

    public function setCity(?City $city = null): self
    {
        $this->city = $city;

        return $this;
    }

    public function getCity(): ?City
    {
        return $this->city;
    }

    public function setCountry(Country $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getCountry(): Country
    {
        return $this->country;
    }

    public function addCatalog(Catalog $catalogs): void
    {
        $this->catalogs[] = $catalogs;
    }

    public function removeCatalog(Catalog $catalogs): void
    {
        $this->catalogs->removeElement($catalogs);
    }

    public function getCatalogs(): Collection
    {
        return $this->catalogs;
    }

    public function addContact(Contact $contacts): void
    {
        $this->contacts[] = $contacts;
    }

    public function removeContact(Contact $contacts): void
    {
        $this->contacts->removeElement($contacts);
    }

    public function getContacts(): Collection
    {
        return $this->contacts;
    }

    public function setInstance(Instance $instance): self
    {
        $this->instance = $instance;

        return $this;
    }

    public function getInstance(): Instance
    {
        return $this->instance;
    }

    public function setCelsiusInstance(?LegacyInstance $celsiusInstance = null): self
    {
        $this->celsiusInstance = $celsiusInstance;

        return $this;
    }

    public function getCelsiusInstance(): ?LegacyInstance
    {
        return $this->celsiusInstance;
    }

    public function setHive(?Hive $hive): self
    {
        $this->hive = $hive;

        return $this;
    }

    public function getHive(): ?Hive
    {
        return $this->hive;
    }

    public function addLibrarian(BaseUser $librarian): self
    {
        $this->librarian[] = $librarian;

        return $this;
    }

    public function removeLibrarian(BaseUser $librarian): void
    {
        $this->librarian->removeElement($librarian);
    }

    public function getLibrarian(): Collection
    {
        return $this->librarian;
    }

    public function findCelsiusInstance(): LegacyInstance|bool
    {
        $institution = $this;
        do {
            $celsius_instance = $institution->getCelsiusInstance();
            $institution = $institution->getParent();
        } while ($institution && !$celsius_instance);

        return $celsius_instance && $celsius_instance->getEnabled() ? $celsius_instance : false;
    }
}
