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
     * @MongoDB\ReferenceOne(targetDocument="BaseUser") 
     */
    protected $operator;

    /**
     * @MongoDB\ReferenceOne(targetDocument="Instance") 
     */
    protected $instance;

    /**
     * @MongoDB\ReferenceMany(targetDocument="File", inversedBy="orders")
     */
    protected $files;

    public function __toString()
    {
        return strval($this->getCode());
    }

    /**
     * @MongoDB\PrePersist 
     */
    public function prePersist()
    {
        $this->setCreated(date('Y-m-d H:i:s'));
    }

    public function __construct()
    {
        $this->files = new \Doctrine\Common\Collections\ArrayCollection();
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
    public function setOperator(\Celsius\Celsius3Bundle\Document\BaseUser $operator)
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

}
