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

use Celsius3\CoreBundle\Entity\Event\Event;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="Celsius3\CoreBundle\Repository\InstanceRepository")
 *
 * @UniqueEntity("url")
 * @UniqueEntity("host")
 */
class Instance extends LegacyInstance
{
    /**
     * @Assert\NotBlank()
     * @Assert\Regex(pattern="/^[a-zA-Z]+$/")
     * @ORM\Column(type="string", length=255, unique=true)
     */
    protected $url;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="string", length=255, unique=true)
     */
    protected $host;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $invisible = false;

    /**
     * @ORM\OneToMany(targetEntity="BaseUser", mappedBy="instance")
     */
    protected $users;

    /**
     * @ORM\OneToMany(targetEntity="Request", mappedBy="instance")
     */
    protected $orders;

    /**
     * @ORM\OneToMany(targetEntity="News", mappedBy="instance")
     */
    protected $news;

    /**
     * @ORM\OneToMany(targetEntity="Contact", mappedBy="instance")
     */
    protected $contacts;

    /**
     * @ORM\OneToMany(targetEntity="Institution", mappedBy="instance")
     */
    protected $institutions;

    /**
     * @ORM\OneToMany(targetEntity="MailTemplate", mappedBy="instance")
     */
    protected $templates;

    /**
     * @ORM\OneToMany(targetEntity="Configuration", mappedBy="instance")
     */
    protected $configurations;

    /**
     * @ORM\OneToMany(targetEntity="Catalog", mappedBy="instance")
     */
    protected $catalogs;

    /**
     * @ORM\OneToMany(targetEntity="Celsius3\CoreBundle\Entity\Event\Event", mappedBy="instance")
     */
    protected $events;

    /**
     * @ORM\OneToMany(targetEntity="State", mappedBy="instance")
     */
    protected $states;

    /**
     * @ORM\OneToMany(targetEntity="Country", mappedBy="instance")
     */
    protected $countries;

    /**
     * @ORM\OneToMany(targetEntity="City", mappedBy="instance")
     */
    protected $cities;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $latitud;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $longitud;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $observaciones;

    /**
     * @ORM\OneToMany(targetEntity="DataRequest", mappedBy="instance")
     */
    protected $dataRequests;

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

    public function isCurrent()
    {
        return true;
    }

    /**
     * Returns the Configuration object associated with key.
     *
     * @param string $key
     *
     * @return Configuration
     */
    public function get($key)
    {
        return $this->getConfigurations()->filter(function (Configuration $entry) use ($key) {
            return $entry->getKey() === $key;
        })->first();
    }

    /**
     * Returns the if the instance has a Configuration with $key.
     *
     * @param string $key
     *
     * @return bool
     */
    public function has($key)
    {
        return $this->getConfigurations()->filter(function (Configuration $entry) use ($key) {
                return $entry->getKey() === $key;
            })->count() > 0;
    }

    /**
     * Set url.
     *
     * @param string $url
     *
     * @return self
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url.
     *
     * @return string $url
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set host.
     *
     * @param string $host
     *
     * @return self
     */
    public function setHost($host)
    {
        $this->host = $host;

        return $this;
    }

    /**
     * Get host.
     *
     * @return string $host
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * Set invisible.
     *
     * @param string $invisible
     *
     * @return self
     */
    public function setInvisible($invisible)
    {
        $this->invisible = $invisible;

        return $this;
    }

    /**
     * Get invisible.
     *
     * @return string $invisible
     */
    public function getInvisible()
    {
        return $this->invisible;
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
     * Add orders.
     *
     * @param Order $orders
     */
    public function addOrder(Request $order)
    {
        $this->orders[] = $order;
    }

    /**
     * Remove orders.
     *
     * @param Order $orders
     */
    public function removeOrder(Request $order)
    {
        $this->orders->removeElement($order);
    }

    /**
     * Get orders.
     *
     * @return Collection $orders
     */
    public function getOrders()
    {
        return $this->orders;
    }

    /**
     * Add news.
     *
     * @param News $news
     */
    public function addNew(News $news)
    {
        $this->news[] = $news;
    }

    /**
     * Remove news.
     *
     * @param News $news
     */
    public function removeNew(News $news)
    {
        $this->news->removeElement($news);
    }

    /**
     * Get news.
     *
     * @return Collection $news
     */
    public function getNews()
    {
        return $this->news;
    }

    /**
     * Add contacts.
     *
     * @param Contact $contacts
     */
    public function addContact(Contact $contact)
    {
        $this->contacts[] = $contact;
    }

    /**
     * Remove contacts.
     *
     * @param Contact $contacts
     */
    public function removeContact(Contact $contact)
    {
        $this->contacts->removeElement($contact);
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
     * Add institutions.
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
     * Add templates.
     *
     * @param MailTemplate $template
     */
    public function addTemplate(MailTemplate $template)
    {
        $this->templates[] = $template;
    }

    /**
     * Remove templates.
     *
     * @param MailTemplate $template
     */
    public function removeTemplate(MailTemplate $template)
    {
        $this->templates->removeElement($template);
    }

    /**
     * Get templates.
     *
     * @return Collection $templates
     */
    public function getTemplates()
    {
        return $this->templates;
    }

    /**
     * Add configurations.
     *
     * @param Configuration $configuration
     */
    public function addConfiguration(Configuration $configuration)
    {
        $this->configurations[] = $configuration;
    }

    /**
     * Remove configurations.
     *
     * @param Configuration $configuration
     */
    public function removeConfiguration(Configuration $configuration)
    {
        $this->configurations->removeElement($configuration);
    }

    /**
     * Get configurations.
     *
     * @return Collection $configurations
     */
    public function getConfigurations()
    {
        return $this->configurations;
    }

    /**
     * Add catalog.
     *
     * @param Catalog $catalog
     */
    public function addCatalog(Catalog $catalog)
    {
        $this->catalogs[] = $catalog;
    }

    /**
     * Remove catalog.
     *
     * @param Catalog $catalog
     */
    public function removeCatalog(Catalog $catalog)
    {
        $this->catalogs->removeElement($catalog);
    }

    /**
     * Get catalogs.
     *
     * @return Collection $catalog
     */
    public function getCatalogs()
    {
        return $this->catalog;
    }

    /**
     * Add event.
     *
     * @param Event $event
     */
    public function addEvent(Event $event)
    {
        $this->events[] = $event;
    }

    /**
     * Remove event.
     *
     * @param Event $event
     */
    public function removeEvent(Event $event)
    {
        $this->events->removeElement($event);
    }

    /**
     * Get events.
     *
     * @return Collection $events
     */
    public function getEvents()
    {
        return $this->events;
    }

    /**
     * Add state.
     *
     * @param State $state
     */
    public function addState(State $state)
    {
        $this->states[] = $state;
    }

    /**
     * Remove state.
     *
     * @param State $state
     */
    public function removeState(State $state)
    {
        $this->states->removeElement($state);
    }

    /**
     * Get states.
     *
     * @return Collection $states
     */
    public function getStates()
    {
        return $this->states;
    }

    /**
     * Add country.
     *
     * @param Country $country
     */
    public function addCountrie(Country $country)
    {
        $this->countries[] = $country;
    }

    /**
     * Remove country.
     *
     * @param Country $country
     */
    public function removeCountrie(Country $country)
    {
        $this->countries->removeElement($country);
    }

    /**
     * Get countries.
     *
     * @return Collection $countries
     */
    public function getCountries()
    {
        return $this->countries;
    }

    /**
     * Add city.
     *
     * @param City $city
     */
    public function addCitie(City $city)
    {
        $this->cities[] = $city;
    }

    /**
     * Remove city.
     *
     * @param City $city
     */
    public function removeCitie(City $city)
    {
        $this->cities->removeElement($city);
    }

    /**
     * Get cities.
     *
     * @return Collection $cities
     */
    public function getCities()
    {
        return $this->cities;
    }

    /**
     * Set observaciones.
     *
     * @param string $observaciones
     *
     * @return Instance
     */
    public function setObservaciones($observaciones)
    {
        $this->observaciones = $observaciones;

        return $this;
    }

    /**
     * Get observaciones.
     *
     * @return string
     */
    public function getObservaciones()
    {
        return $this->observaciones;
    }

    /**
     * Add news.
     *
     * @param News $news
     *
     * @return Instance
     */
    public function addNews(News $news)
    {
        $this->news[] = $news;

        return $this;
    }

    /**
     * Remove news.
     *
     * @param News $news
     */
    public function removeNews(News $news)
    {
        $this->news->removeElement($news);
    }

    /**
     * Set latitud.
     *
     * @param string $latitud
     *
     * @return Instance
     */
    public function setLatitud($latitud)
    {
        $this->latitud = $latitud;

        return $this;
    }

    /**
     * Get latitud.
     *
     * @return string
     */
    public function getLatitud()
    {
        return $this->latitud;
    }

    /**
     * Set longitud.
     *
     * @param string $longitud
     *
     * @return Instance
     */
    public function setLongitud($longitud)
    {
        $this->longitud = $longitud;

        return $this;
    }

    /**
     * Get longitud.
     *
     * @return string
     */
    public function getLongitud()
    {
        return $this->longitud;
    }

    /**
     * Add country.
     *
     * @param Country $country
     *
     * @return Instance
     */
    public function addCountry(Country $country)
    {
        $this->countries[] = $country;

        return $this;
    }

    /**
     * Remove country.
     *
     * @param Country $country
     */
    public function removeCountry(Country $country)
    {
        $this->countries->removeElement($country);
    }

    /**
     * Add city.
     *
     * @param City $city
     *
     * @return Instance
     */
    public function addCity(City $city)
    {
        $this->cities[] = $city;

        return $this;
    }

    /**
     * Remove city.
     *
     * @param City $city
     */
    public function removeCity(City $city)
    {
        $this->cities->removeElement($city);
    }

    /**
     * Add data request.
     *
     * @param DataRequest $dataRequest
     */
    public function addDataRequest(DataRequest $dataRequest)
    {
        $this->dataRequests[] = $dataRequest;
    }

    /**
     * Remove data request.
     *
     * @param DataRequest $dataRequest
     */
    public function removeDataRequest(DataRequest $dataRequest)
    {
        $this->institutions->removeElement($dataRequest);
    }

    /**
     * Get data requests.
     *
     * @return Collection $dataRequests
     */
    public function getDataRequests()
    {
        return $this->dataRequests;
    }
}
