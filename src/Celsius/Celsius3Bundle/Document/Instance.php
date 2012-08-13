<?php

namespace Celsius\Celsius3Bundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @MongoDB\Document
 * @MongoDB\Document(repositoryClass="Celsius\Celsius3Bundle\Repository\InstanceRepository")
 */
class Instance
{

    /**
     * @MongoDB\Id
     */
    protected $id;

    /**
     * @Assert\NotBlank()
     * @MongoDB\String
     */
    protected $name;

    /**
     * @Assert\NotBlank()
     * @MongoDB\String
     */
    protected $abbreviation;

    /**
     * @Assert\NotBlank()
     * @MongoDB\String
     */
    protected $url;

    /**
     * @Assert\NotBlank()
     * @MongoDB\String
     */
    protected $website;

    /**
     * @Assert\NotBlank()
     * @MongoDB\String
     */
    protected $title;

    /**
     * @Assert\NotBlank()
     * @Assert\Email()
     * @MongoDB\String
     */
    protected $email;

    /**
     * @MongoDB\ReferenceMany(targetDocument="BaseUser", mappedBy="instance")
     */
    protected $users;

    /**
     * @MongoDB\ReferenceMany(targetDocument="Order", mappedBy="instance")
     */
    protected $orders;

    /**
     * @MongoDB\ReferenceMany(targetDocument="News", mappedBy="instance", sort={"date"="desc"})
     */
    protected $news;

    /**
     * @MongoDB\ReferenceMany(targetDocument="Contact", mappedBy="instance")
     */
    protected $contacts;

    /**
     * @MongoDB\ReferenceMany(targetDocument="Institution", mappedBy="instance")
     */
    protected $institutions;

    /**
     * @MongoDB\ReferenceMany(targetDocument="MailTemplate", mappedBy="instance")
     */
    protected $templates;

    /**
     * @MongoDB\ReferenceMany(targetDocument="Configuration", mappedBy="instance")
     */
    private $configurations;

    /**
     * @MongoDB\ReferenceMany(targetDocument="Catalog", mappedBy="instance")
     */
    private $catalogs;

    /**
     * @MongoDB\ReferenceMany(targetDocument="Notification", mappedBy="target")
     */
    private $notifications;

    /**
     * @MongoDB\ReferenceMany(targetDocument="Event", mappedBy="instance")
     */
    private $events;

    /**
     * @MongoDB\ReferenceMany(targetDocument="State", mappedBy="instance")
     */
    private $states;

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
        $this->templates = new \Doctrine\Common\Collections\ArrayCollection();
        $this->configurations = new \Doctrine\Common\Collections\ArrayCollection();
        $this->catalogs = new \Doctrine\Common\Collections\ArrayCollection();
        $this->notifications = new \Doctrine\Common\Collections\ArrayCollection();
        $this->events = new \Doctrine\Common\Collections\ArrayCollection();
        $this->states = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Instance
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
     * @return Instance
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
     * @return Instance
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
     * @return Instance
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
     * Set title
     *
     * @param string $title
     * @return Instance
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * Get title
     *
     * @return string $title
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Instance
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
     * Add users
     *
     * @param Celsius\Celsius3Bundle\Document\BaseUser $users
     */
    public function addUsers(\Celsius\Celsius3Bundle\Document\BaseUser $users)
    {
        $this->users[] = $users;
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
     * @param Celsius\Celsius3Bundle\Document\Order $orders
     */
    public function addOrders(\Celsius\Celsius3Bundle\Document\Order $orders)
    {
        $this->orders[] = $orders;
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
     * @param Celsius\Celsius3Bundle\Document\News $news
     */
    public function addNews(\Celsius\Celsius3Bundle\Document\News $news)
    {
        $this->news[] = $news;
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
     * @param Celsius\Celsius3Bundle\Document\Contact $contacts
     */
    public function addContacts(\Celsius\Celsius3Bundle\Document\Contact $contacts)
    {
        $this->contacts[] = $contacts;
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
     * @param Celsius\Celsius3Bundle\Document\Institution $institutions
     */
    public function addInstitutions(\Celsius\Celsius3Bundle\Document\Institution $institutions)
    {
        $this->institutions[] = $institutions;
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
     * @param Celsius\Celsius3Bundle\Document\MailTemplate $templates
     */
    public function addTemplates(\Celsius\Celsius3Bundle\Document\MailTemplate $templates)
    {
        $this->templates[] = $templates;
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
     * @param Celsius\Celsius3Bundle\Document\Configuration $configurations
     */
    public function addConfigurations(\Celsius\Celsius3Bundle\Document\Configuration $configurations)
    {
        $this->configurations[] = $configurations;
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
     * @param Celsius\Celsius3Bundle\Document\Catalog $catalogs
     */
    public function addCatalogs(\Celsius\Celsius3Bundle\Document\Catalog $catalogs)
    {
        $this->catalogs[] = $catalogs;
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
     * Add notifications
     *
     * @param Celsius\Celsius3Bundle\Document\Notification $notifications
     */
    public function addNotifications(\Celsius\Celsius3Bundle\Document\Notification $notifications)
    {
        $this->notifications[] = $notifications;
    }

    /**
     * Get notifications
     *
     * @return Doctrine\Common\Collections\Collection $notifications
     */
    public function getNotifications()
    {
        return $this->notifications;
    }


    /**
     * Add events
     *
     * @param Celsius\Celsius3Bundle\Document\Event $events
     */
    public function addEvents(\Celsius\Celsius3Bundle\Document\Event $events)
    {
        $this->events[] = $events;
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
     * @param Celsius\Celsius3Bundle\Document\State $states
     */
    public function addStates(\Celsius\Celsius3Bundle\Document\State $states)
    {
        $this->states[] = $states;
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
}
