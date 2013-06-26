<?php

namespace Celsius3\CoreBundle\Document;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @MongoDB\Document
 * @MongoDB\Document(repositoryClass="Celsius3\CoreBundle\Repository\InstanceRepository")
 */
class Instance
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
     * @Assert\NotBlank()
     * @MongoDB\String
     */
    private $abbreviation;

    /**
     * @Assert\NotBlank()
     * @MongoDB\UniqueIndex(order="asc")
     * @MongoDB\String
     */
    private $url;

    /**
     * @Assert\NotBlank()
     * @Assert\Url()
     * @MongoDB\String
     */
    private $website;

    /**
     * @Assert\NotBlank()
     * @Assert\Email()
     * @MongoDB\String
     */
    private $email;

    /**
     * @Assert\NotBlank()
     * @Assert\Type(type="boolean")
     * @MongoDB\Boolean
     */
    private $enabled = true;

    /**
     * @MongoDB\ReferenceMany(targetDocument="BaseUser", mappedBy="instance")
     */
    private $users;

    /**
     * @MongoDB\ReferenceMany(targetDocument="Order", mappedBy="instance")
     */
    private $orders;

    /**
     * @MongoDB\ReferenceMany(targetDocument="News", mappedBy="instance", sort={"date"="desc"})
     */
    private $news;

    /**
     * @MongoDB\ReferenceMany(targetDocument="Contact", mappedBy="instance")
     */
    private $contacts;

    /**
     * @MongoDB\ReferenceMany(targetDocument="Institution", mappedBy="instance")
     */
    private $institutions;

    /**
     * @MongoDB\ReferenceMany(targetDocument="Institution", mappedBy="celsiusInstance")
     */
    private $ownerInstitutions;

    /**
     * @MongoDB\ReferenceMany(targetDocument="MailTemplate", mappedBy="instance")
     */
    private $templates;

    /**
     * @MongoDB\ReferenceMany(targetDocument="Configuration", mappedBy="instance", sort={"key"="asc"})
     */
    private $configurations;

    /**
     * @MongoDB\ReferenceMany(targetDocument="Catalog", mappedBy="instance")
     */
    private $catalogs;

    /**
     * @MongoDB\ReferenceMany(targetDocument="Event", mappedBy="instance")
     */
    private $events;

    /**
     * @MongoDB\ReferenceMany(targetDocument="State", mappedBy="instance")
     */
    private $states;

    /**
     * @MongoDB\ReferenceMany(targetDocument="Country", mappedBy="instance")
     */
    private $countries;

    /**
     * @MongoDB\ReferenceMany(targetDocument="City", mappedBy="instance")
     */
    private $cities;

    public function __toString()
    {
        return $this->getName();
    }

    public function __construct()
    {
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
        $this->orders = new \Doctrine\Common\Collections\ArrayCollection();
        $this->news = new \Doctrine\Common\Collections\ArrayCollection();
        $this->contacts = new \Doctrine\Common\Collections\ArrayCollection();
        $this->institutions = new \Doctrine\Common\Collections\ArrayCollection();
        $this->ownerInstitutions = new \Doctrine\Common\Collections\ArrayCollection();
        $this->templates = new \Doctrine\Common\Collections\ArrayCollection();
        $this->configurations = new \Doctrine\Common\Collections\ArrayCollection();
        $this->catalogs = new \Doctrine\Common\Collections\ArrayCollection();
        $this->events = new \Doctrine\Common\Collections\ArrayCollection();
        $this->states = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Returns the Configuration object associated with key
     *
     * @param string $key
     * @return Configuration
     */
    public function get($key)
    {
        return $this->getConfigurations()
                ->filter(
                        function ($entry) use ($key)
                        {
                            return ($entry->getKey() == $key);
                        })->first();
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
     * Set url
     *
     * @param string $url
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
     * Set email
     *
     * @param string $email
     * @return self
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * Get email
     *
     * @return string $email
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set enabled
     *
     * @param boolean $enabled
     * @return self
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
        return $this;
    }

    /**
     * Get enabled
     *
     * @return boolean $enabled
     */
    public function getEnabled()
    {
        return $this->enabled;
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
     * Add ownerInstitutions
     *
     * @param Celsius3\CoreBundle\Document\Institution $ownerInstitutions
     */
    public function addOwnerInstitution(
            \Celsius3\CoreBundle\Document\Institution $ownerInstitutions)
    {
        $this->ownerInstitutions[] = $ownerInstitutions;
    }

    /**
     * Remove ownerInstitutions
     *
     * @param Celsius3\CoreBundle\Document\Institution $ownerInstitutions
     */
    public function removeOwnerInstitution(
            \Celsius3\CoreBundle\Document\Institution $ownerInstitutions)
    {
        $this->ownerInstitutions->removeElement($ownerInstitutions);
    }

    /**
     * Get ownerInstitutions
     *
     * @return Doctrine\Common\Collections\Collection $ownerInstitutions
     */
    public function getOwnerInstitutions()
    {
        return $this->ownerInstitutions;
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
     * @param Celsius3\CoreBundle\Document\Event $events
     */
    public function addEvent(\Celsius3\CoreBundle\Document\Event $events)
    {
        $this->events[] = $events;
    }

    /**
     * Remove events
     *
     * @param Celsius3\CoreBundle\Document\Event $events
     */
    public function removeEvent(\Celsius3\CoreBundle\Document\Event $events)
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
