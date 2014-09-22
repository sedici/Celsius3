<?php

/*
 * Celsius3 - Order management
 * Copyright (C) 2014 PrEBi <info@prebi.unlp.edu.ar>
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

namespace Celsius3\CoreBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Timestampable\Traits\TimestampableDocument;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ODM\Document
 * @ODM\Document(repositoryClass="Celsius3\CoreBundle\Repository\InstanceRepository")
 */
class Instance extends LegacyInstance
{
    use TimestampableDocument;
    /**
     * @Assert\NotBlank()
     * @ODM\String
     */
    protected $url;
    /**
     * @ODM\ReferenceMany(targetDocument="BaseUser", mappedBy="instance")
     */
    protected $users;
    /**
     * @ODM\ReferenceMany(targetDocument="Order", mappedBy="instance")
     */
    protected $orders;
    /**
     * @ODM\ReferenceMany(targetDocument="News", mappedBy="instance", sort={"date"="desc"})
     */
    protected $news;
    /**
     * @ODM\ReferenceMany(targetDocument="Contact", mappedBy="instance")
     */
    protected $contacts;
    /**
     * @ODM\ReferenceMany(targetDocument="Institution", mappedBy="instance")
     */
    protected $institutions;
    /**
     * @ODM\ReferenceMany(targetDocument="MailTemplate", mappedBy="instance")
     */
    protected $templates;
    /**
     * @ODM\ReferenceMany(targetDocument="Configuration", mappedBy="instance", sort={"key"="asc"})
     */
    protected $configurations;
    /**
     * @ODM\ReferenceMany(targetDocument="Catalog", mappedBy="instance")
     */
    protected $catalogs;
    /**
     * @ODM\ReferenceMany(targetDocument="Celsius3\CoreBundle\Document\Event\Event", mappedBy="instance")
     */
    protected $events;
    /**
     * @ODM\ReferenceMany(targetDocument="State", mappedBy="instance")
     */
    protected $states;
    /**
     * @ODM\ReferenceMany(targetDocument="Country", mappedBy="instance")
     */
    protected $countries;
    /**
     * @ODM\ReferenceMany(targetDocument="City", mappedBy="instance")
     */
    protected $cities;

    public function __construct()
    {
        parent::__construct();
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
        $this->orders = new \Doctrine\Common\Collections\ArrayCollection();
        $this->news = new \Doctrine\Common\Collections\ArrayCollection();
        $this->contacts = new \Doctrine\Common\Collections\ArrayCollection();
        $this->institutions = new \Doctrine\Common\Collections\ArrayCollection();
        $this->templates = new \Doctrine\Common\Collections\ArrayCollection();
        $this->configurations = new \Doctrine\Common\Collections\ArrayCollection();
        $this->catalogs = new \Doctrine\Common\Collections\ArrayCollection();
        $this->events = new \Doctrine\Common\Collections\ArrayCollection();
        $this->states = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function isCurrent()
    {
        return true;
    }

    /**
     * Returns the Configuration object associated with key
     *
     * @param  string        $key
     * @return Configuration
     */
    public function get($key)
    {
        return $this->getConfigurations()
                        ->filter(
                                function ($entry) use ($key) {
                            return ($entry->getKey() == $key);
                        })->first();
    }

    /**
     * Set url
     *
     * @param  string $url
     * @return self
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string $url
     */
    public function getUrl()
    {
        return $this->url;
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
     * Add orders
     *
     * @param Celsius3\CoreBundle\Document\Order $orders
     */
    public function addOrder(\Celsius3\CoreBundle\Document\Order $orders)
    {
        $this->orders[] = $orders;
    }

    /**
     * Remove orders
     *
     * @param Celsius3\CoreBundle\Document\Order $orders
     */
    public function removeOrder(\Celsius3\CoreBundle\Document\Order $orders)
    {
        $this->orders->removeElement($orders);
    }

    /**
     * Get orders
     *
     * @return Doctrine\Common\Collections\Collection $orders
     */
    public function getOrders()
    {
        return $this->orders;
    }

    /**
     * Add news
     *
     * @param Celsius3\CoreBundle\Document\News $news
     */
    public function addNew(\Celsius3\CoreBundle\Document\News $news)
    {
        $this->news[] = $news;
    }

    /**
     * Remove news
     *
     * @param Celsius3\CoreBundle\Document\News $news
     */
    public function removeNew(\Celsius3\CoreBundle\Document\News $news)
    {
        $this->news->removeElement($news);
    }

    /**
     * Get news
     *
     * @return Doctrine\Common\Collections\Collection $news
     */
    public function getNews()
    {
        return $this->news;
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
     * Add templates
     *
     * @param Celsius3\CoreBundle\Document\MailTemplate $templates
     */
    public function addTemplate(
    \Celsius3\CoreBundle\Document\MailTemplate $templates)
    {
        $this->templates[] = $templates;
    }

    /**
     * Remove templates
     *
     * @param Celsius3\CoreBundle\Document\MailTemplate $templates
     */
    public function removeTemplate(
    \Celsius3\CoreBundle\Document\MailTemplate $templates)
    {
        $this->templates->removeElement($templates);
    }

    /**
     * Get templates
     *
     * @return Doctrine\Common\Collections\Collection $templates
     */
    public function getTemplates()
    {
        return $this->templates;
    }

    /**
     * Add configurations
     *
     * @param Celsius3\CoreBundle\Document\Configuration $configurations
     */
    public function addConfiguration(
    \Celsius3\CoreBundle\Document\Configuration $configurations)
    {
        $this->configurations[] = $configurations;
    }

    /**
     * Remove configurations
     *
     * @param Celsius3\CoreBundle\Document\Configuration $configurations
     */
    public function removeConfiguration(
    \Celsius3\CoreBundle\Document\Configuration $configurations)
    {
        $this->configurations->removeElement($configurations);
    }

    /**
     * Get configurations
     *
     * @return Doctrine\Common\Collections\Collection $configurations
     */
    public function getConfigurations()
    {
        return $this->configurations;
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
     * Add events
     *
     * @param Celsius3\CoreBundle\Document\Event\Event $events
     */
    public function addEvent(\Celsius3\CoreBundle\Document\Event\Event $events)
    {
        $this->events[] = $events;
    }

    /**
     * Remove events
     *
     * @param Celsius3\CoreBundle\Document\Event\Event $events
     */
    public function removeEvent(\Celsius3\CoreBundle\Document\Event\Event $events)
    {
        $this->events->removeElement($events);
    }

    /**
     * Get events
     *
     * @return Doctrine\Common\Collections\Collection $events
     */
    public function getEvents()
    {
        return $this->events;
    }

    /**
     * Add states
     *
     * @param Celsius3\CoreBundle\Document\State $states
     */
    public function addState(\Celsius3\CoreBundle\Document\State $states)
    {
        $this->states[] = $states;
    }

    /**
     * Remove states
     *
     * @param Celsius3\CoreBundle\Document\State $states
     */
    public function removeState(\Celsius3\CoreBundle\Document\State $states)
    {
        $this->states->removeElement($states);
    }

    /**
     * Get states
     *
     * @return Doctrine\Common\Collections\Collection $states
     */
    public function getStates()
    {
        return $this->states;
    }

    /**
     * Add countries
     *
     * @param Celsius3\CoreBundle\Document\Country $countries
     */
    public function addCountrie(
    \Celsius3\CoreBundle\Document\Country $countries)
    {
        $this->countries[] = $countries;
    }

    /**
     * Remove countries
     *
     * @param Celsius3\CoreBundle\Document\Country $countries
     */
    public function removeCountrie(
    \Celsius3\CoreBundle\Document\Country $countries)
    {
        $this->countries->removeElement($countries);
    }

    /**
     * Get countries
     *
     * @return Doctrine\Common\Collections\Collection $countries
     */
    public function getCountries()
    {
        return $this->countries;
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
}