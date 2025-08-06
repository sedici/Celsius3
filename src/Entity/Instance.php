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

declare(strict_types=1);

namespace Celsius3\Entity;

use Celsius3\Entity\Event\Event;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;
use Celsius3\Repository\InstanceRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Event\PostPersistEventArgs;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;

#[
    Entity(repositoryClass: InstanceRepository::class),

    UniqueEntity('url'),
    UniqueEntity('host'),

    HasLifecycleCallbacks
]
class Instance extends LegacyInstance
{

    #[Assert\NotBlank]
    #[Assert\Regex(pattern: "/^[a-zA-Z]+$/")]
    #[ORM\Column(type: 'string', length: 255, unique: true)]
    protected string $url;


    #[Assert\NotBlank]
    #[ORM\Column(type: 'string', length: 255, unique: true)]
    protected string $host;


    #[ORM\Column(type: 'boolean', nullable: true)]
    protected ?bool $invisible = false;


    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    protected ?string $latitud = null;


    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    protected ?string $longitud = null;


    #[ORM\Column(type: 'string', nullable: true)]
    protected ?string $observaciones = null;


    #[ORM\OneToMany(targetEntity: BaseUser::class, mappedBy: 'instance', fetch: "EXTRA_LAZY")]
    protected Collection $users;


    #[ORM\OneToMany(targetEntity: Request::class, mappedBy: 'instance', fetch: "EXTRA_LAZY")]
    protected Collection $orders;


    #[ORM\OneToMany(targetEntity: News::class, mappedBy: 'instance', fetch: "EXTRA_LAZY")]
    protected Collection $news;


    #[ORM\OneToMany(targetEntity: Contact::class, mappedBy: 'instance', fetch: "EXTRA_LAZY")]
    protected Collection $contacts;


    #[ORM\OneToMany(targetEntity: Institution::class, mappedBy: 'instance', fetch: "EXTRA_LAZY")]
    protected Collection $institutions;


    #[ORM\OneToMany(targetEntity: EmailTemplate::class, mappedBy: 'instance', fetch: "EXTRA_LAZY")]
    protected Collection $templates;


    #[ORM\OneToMany(targetEntity: Configuration::class, mappedBy: 'instance', fetch: "EXTRA_LAZY")]
    protected Collection $configurations;


    #[ORM\OneToMany(targetEntity: Catalog::class, mappedBy: 'instance', fetch: "EXTRA_LAZY")]
    protected Collection $catalogs;


    #[ORM\OneToMany(targetEntity: Event::class, mappedBy: 'instance', fetch: "EXTRA_LAZY")]
    protected Collection $events;


    #[ORM\OneToMany(targetEntity: State::class, mappedBy: 'instance', fetch: "EXTRA_LAZY")]
    protected Collection $states;


    #[ORM\OneToMany(targetEntity: Country::class, mappedBy: 'instance', fetch: "EXTRA_LAZY")]
    protected Collection $countries;


    #[ORM\OneToMany(targetEntity: City::class, mappedBy: 'instance', fetch: "EXTRA_LAZY")]
    protected Collection $cities;


    #[ORM\OneToMany(targetEntity: DataRequest::class, mappedBy: 'instance', fetch: "EXTRA_LAZY")]
    protected Collection $dataRequests;

    // ----

    public function __construct()
    {
        parent::__construct();
        $this->users = new ArrayCollection();
        $this->orders = new ArrayCollection();
        $this->news = new ArrayCollection();
        $this->contacts = new ArrayCollection();
        $this->institutions = new ArrayCollection();
        $this->templates = new ArrayCollection();
        $this->configurations = new ArrayCollection();
        $this->catalogs = new ArrayCollection();
        $this->events = new ArrayCollection();
        $this->states = new ArrayCollection();
        $this->countries = new ArrayCollection();
        $this->cities = new ArrayCollection();
        $this->dataRequests = new ArrayCollection();
    }

    public function isCurrent(): bool
    {
        return true;
    }

    public function get($key): Configuration
    {
        $cfg = $this->getConfigurations()->filter(
            fn (Configuration $entry): bool => $entry->getKey() === $key
        )->first();

        return $cfg ?: new Configuration();
    }

    public function getConfigurations(): array|Collection
    { return $this->configurations; }

    public function has($key): bool
    {
        return $this->getConfigurations()
            ->filter(
                fn (Configuration $entry): bool => $entry->getKey() === $key
            )->count() > 0;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getHost(): ?string
    {
        return $this->host;
    }

    public function setHost(string $host): self
    {
        $this->host = $host;

        return $this;
    }

    public function getInvisible(): bool
    {
        return $this->invisible;
    }

    public function setInvisible(bool $invisible): self
    {
        $this->invisible = $invisible;

        return $this;
    }

    public function addUser(BaseUser $user): void
    {
        $this->users[] = $user;
    }

    public function removeUser(BaseUser $user): void
    {
        $this->users->removeElement($user);
    }

    public function getUsers()
    {
        return $this->users;
    }

    public function addOrder(Request $order): void
    {
        $this->orders[] = $order;
    }

    public function removeOrder(Request $order): void
    {
        $this->orders->removeElement($order);
    }

    public function getOrders()
    {
        return $this->orders;
    }

    public function addNew(News $news): void
    {
        $this->news[] = $news;
    }

    public function removeNew(News $news): void
    {
        $this->news->removeElement($news);
    }

    public function getNews()
    {
        return $this->news;
    }

    public function addContact(Contact $contact): void
    {
        $this->contacts[] = $contact;
    }

    public function removeContact(Contact $contact): void
    {
        $this->contacts->removeElement($contact);
    }

    public function getContacts()
    {
        return $this->contacts;
    }

    public function addInstitution(Institution $institution): void
    {
        $this->institutions[] = $institution;
    }

    public function removeInstitution(Institution $institution): void
    {
        $this->institutions->removeElement($institution);
    }

    public function getInstitutions()
    {
        return $this->institutions;
    }

    public function addTemplate(EmailTemplate $template): void
    {
        $this->templates[] = $template;
    }

    public function removeTemplate(EmailTemplate $template): void
    {
        $this->templates->removeElement($template);
    }

    public function getTemplates()
    {
        return $this->templates;
    }

    public function addConfiguration(Configuration $configuration): void
    {
        $this->configurations[] = $configuration;
    }

    public function removeConfiguration(Configuration $configuration): void
    {
        $this->configurations->removeElement($configuration);
    }

    public function addCatalog(Catalog $catalog): void
    {
        $this->catalogs[] = $catalog;
    }

    public function removeCatalog(Catalog $catalog): void
    {
        $this->catalogs->removeElement($catalog);
    }

    public function getCatalogs()
    {
        return $this->catalogs;
    }

    public function addEvent(Event $event): void
    {
        $this->events[] = $event;
    }

    public function removeEvent(Event $event): void
    {
        $this->events->removeElement($event);
    }

    public function getEvents()
    {
        return $this->events;
    }

    public function addState(State $state): void
    {
        $this->states[] = $state;
    }

    public function removeState(State $state): void
    {
        $this->states->removeElement($state);
    }

    public function getStates()
    {
        return $this->states;
    }

    public function getCountries()
    {
        return $this->countries;
    }

    public function addCitie(City $city): void
    {
        $this->cities[] = $city;
    }

    public function removeCitie(City $city): void
    {
        $this->cities->removeElement($city);
    }

    public function getCities()
    {
        return $this->cities;
    }

    public function getObservaciones()
    {
        return $this->observaciones;
    }

    public function setObservaciones($observaciones): Instance
    {
        $this->observaciones = $observaciones;

        return $this;
    }

    public function addNews(News $news): Instance
    {
        $this->news[] = $news;

        return $this;
    }

    public function removeNews(News $news): void
    {
        $this->news->removeElement($news);
    }

    public function getLatitud(): ?string
    {
        return $this->latitud;
    }

    public function setLatitud(string $latitud): Instance
    {
        $this->latitud = $latitud;

        return $this;
    }

    public function getLongitud(): ?string
    {
        return $this->longitud;
    }

    public function setLongitud(string $longitud): Instance
    {
        $this->longitud = $longitud;

        return $this;
    }

    public function addCountry(Country $country): Instance
    {
        $this->countries[] = $country;

        return $this;
    }

    public function removeCountry(Country $country): void
    {
        $this->countries->removeElement($country);
    }

    public function addCity(City $city): Instance
    {
        $this->cities[] = $city;

        return $this;
    }

    public function removeCity(City $city): void
    {
        $this->cities->removeElement($city);
    }

    public function addDataRequest(DataRequest $dataRequest): void
    {
        $this->dataRequests[] = $dataRequest;
    }

    public function removeDataRequest(DataRequest $dataRequest): void
    {
        $this->institutions->removeElement($dataRequest);
    }

    public function getDataRequests()
    {
        return $this->dataRequests;
    }
}
