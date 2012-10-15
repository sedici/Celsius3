<?php

namespace Celsius\Celsius3Bundle\Document;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document
 * @MongoDB\Document(repositoryClass="Celsius\Celsius3Bundle\Repository\OrderRepository")
 */
class Order
{

    /**
     * @MongoDB\Id
     */
    protected $id;

    /**
     * @Assert\Type(type="integer")
     * @MongoDB\Int
     */
    protected $code;

    /**
     * @Assert\NotBlank()
     * @Assert\Type(type="integer")
     * @MongoDB\Int
     */
    protected $type;

    /**
     * @Assert\Date()
     * @MongoDB\Date
     */
    protected $created;

    /**
     * @Assert\Date()
     * @MongoDB\Date
     */
    protected $searched;

    /**
     * @Assert\Date()
     * @MongoDB\Date
     */
    protected $requested;

    /**
     * @Assert\Date()
     * @MongoDB\Date
     */
    protected $received;

    /**
     * @Assert\Date()
     * @MongoDB\Date
     */
    protected $delivered;

    /**
     * @Assert\Date()
     * @MongoDB\Date
     */
    protected $canceled;

    /**
     * @Assert\Date()
     * @MongoDB\Date
     */
    protected $annuled;

    /**
     * @MongoDB\String
     */
    protected $comments;

    /**
     * @MongoDB\EmbedOne(targetDocument="MaterialType")
     */
    protected $materialData;

    /**
     * @MongoDB\ReferenceOne(targetDocument="BaseUser") 
     */
    protected $owner;

    /**
     * @MongoDB\ReferenceOne(targetDocument="Admin") 
     */
    protected $operator;

    /**
     * @MongoDB\ReferenceOne(targetDocument="Librarian") 
     */
    protected $librarian;

    /**
     * @MongoDB\ReferenceOne(targetDocument="Instance") 
     */
    protected $instance;

    /**
     * @MongoDB\ReferenceMany(targetDocument="File", inversedBy="orders")
     */
    protected $files;

    /**
     * @MongoDB\ReferenceMany(targetDocument="Event", mappedBy="order")
     */
    protected $events;

    /**
     * @MongoDB\ReferenceMany(targetDocument="State", mappedBy="order")
     */
    protected $states;

    /**
     * @MongoDB\ReferenceOne(targetDocument="State") 
     */
    protected $currentState;

    public function __toString()
    {
        return strval($this->getCode());
    }

    public function __construct()
    {
        $this->files = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set code
     *
     * @param int $code
     * @return Order
     */
    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    /**
     * Get code
     *
     * @return int $code
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set type
     *
     * @param int $type
     * @return Order
     */
    public function setType($type)
    {
        $this->type = intval($type);
        return $this;
    }

    /**
     * Get type
     *
     * @return int $type
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set created
     *
     * @param date $created
     * @return Order
     */
    public function setCreated($created)
    {
        $this->created = $created;
        return $this;
    }

    /**
     * Get created
     *
     * @return date $created
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set searched
     *
     * @param date $searched
     * @return Order
     */
    public function setSearched($searched)
    {
        $this->searched = $searched;
        return $this;
    }

    /**
     * Get searched
     *
     * @return date $searched
     */
    public function getSearched()
    {
        return $this->searched;
    }

    /**
     * Set requested
     *
     * @param date $requested
     * @return Order
     */
    public function setRequested($requested)
    {
        $this->requested = $requested;
        return $this;
    }

    /**
     * Get requested
     *
     * @return date $requested
     */
    public function getRequested()
    {
        return $this->requested;
    }

    /**
     * Set received
     *
     * @param date $received
     * @return Order
     */
    public function setReceived($received)
    {
        $this->received = $received;
        return $this;
    }

    /**
     * Get received
     *
     * @return date $received
     */
    public function getReceived()
    {
        return $this->received;
    }

    /**
     * Set delivered
     *
     * @param date $delivered
     * @return Order
     */
    public function setDelivered($delivered)
    {
        $this->delivered = $delivered;
        return $this;
    }

    /**
     * Get delivered
     *
     * @return date $delivered
     */
    public function getDelivered()
    {
        return $this->delivered;
    }

    /**
     * Set comments
     *
     * @param string $comments
     * @return Order
     */
    public function setComments($comments)
    {
        $this->comments = $comments;
        return $this;
    }

    /**
     * Get comments
     *
     * @return string $comments
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Set materialData
     *
     * @param Celsius\Celsius3Bundle\Document\MaterialType $materialData
     * @return Order
     */
    public function setMaterialData($materialData)
    {
        $this->materialData = $materialData;
        return $this;
    }

    /**
     * Get materialData
     *
     * @return Celsius\Celsius3Bundle\Document\MaterialType $materialData
     */
    public function getMaterialData()
    {
        return $this->materialData;
    }

    /**
     * Set owner
     *
     * @param Celsius\Celsius3Bundle\Document\BaseUser $owner
     * @return Order
     */
    public function setOwner($owner)
    {
        $this->owner = $owner;
        return $this;
    }

    /**
     * Get owner
     *
     * @return Celsius\Celsius3Bundle\Document\BaseUser $owner
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * Set operator
     *
     * @param Celsius\Celsius3Bundle\Document\BaseUser $operator
     * @return Order
     */
    public function setOperator(\Celsius\Celsius3Bundle\Document\Admin $operator)
    {
        $this->operator = $operator;
        return $this;
    }

    /**
     * Get operator
     *
     * @return Celsius\Celsius3Bundle\Document\BaseUser $operator
     */
    public function getOperator()
    {
        return $this->operator;
    }

    /**
     * Set instance
     *
     * @param Celsius\Celsius3Bundle\Document\Instance $instance
     * @return Order
     */
    public function setInstance(\Celsius\Celsius3Bundle\Document\Instance $instance)
    {
        $this->instance = $instance;
        return $this;
    }

    /**
     * Get instance
     *
     * @return Celsius\Celsius3Bundle\Document\Instance $instance
     */
    public function getInstance()
    {
        return $this->instance;
    }

    /**
     * Add files
     *
     * @param Celsius\Celsius3Bundle\Document\File $files
     */
    public function addFiles($files)
    {
        $this->files[] = $files;
    }

    /**
     * Get files
     *
     * @return Doctrine\Common\Collections\Collection $files
     */
    public function getFiles()
    {
        return $this->files;
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

    /**
     * Set librarian
     *
     * @param Celsius\Celsius3Bundle\Document\Librarian $librarian
     * @return Order
     */
    public function setLibrarian(\Celsius\Celsius3Bundle\Document\Librarian $librarian)
    {
        $this->librarian = $librarian;
        return $this;
    }

    /**
     * Get librarian
     *
     * @return Celsius\Celsius3Bundle\Document\Librarian $librarian
     */
    public function getLibrarian()
    {
        return $this->librarian;
    }

    /**
     * Set currentState
     *
     * @param Celsius\Celsius3Bundle\Document\State $currentState
     * @return Order
     */
    public function setCurrentState(\Celsius\Celsius3Bundle\Document\State $currentState)
    {
        $this->currentState = $currentState;
        return $this;
    }

    /**
     * Get currentState
     *
     * @return Celsius\Celsius3Bundle\Document\State $currentState
     */
    public function getCurrentState()
    {
        return $this->currentState;
    }

    public function hasState($name)
    {
        return ($this->getStates()->filter(
                        function($entry) use ($name)
                        {
                            return ($entry->getType()->getName() == $name);
                        }
                )->count() > 0);
    }

    public function getState($name)
    {
        return $this->getStates()->filter(
                        function($entry) use ($name)
                        {
                            return ($entry->getType()->getName() == $name);
                        }
                )->first();
    }

    /**
     * Set canceled
     *
     * @param date $canceled
     * @return Order
     */
    public function setCanceled($canceled)
    {
        $this->canceled = $canceled;
        return $this;
    }

    /**
     * Get canceled
     *
     * @return date $canceled
     */
    public function getCanceled()
    {
        return $this->canceled;
    }

    /**
     * Set annuled
     *
     * @param date $annuled
     * @return Order
     */
    public function setAnnuled($annuled)
    {
        $this->annuled = $annuled;
        return $this;
    }

    /**
     * Get annuled
     *
     * @return date $annuled
     */
    public function getAnnuled()
    {
        return $this->annuled;
    }

}
