<?php

namespace Celsius\Celsius3Bundle\Document;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document
 */
class CatalogSearch
{
    /**
     * @MongoDB\Id
     */
    private $id;

    /**
     * @Assert\NotBlank
     * @MongoDB\String
     */
    private $result;

    /**
     * @Assert\NotNull
     * @MongoDB\Date
     */
    private $date;

    /**
     * @Assert\NotNull
     * @MongoDB\ReferenceOne(targetDocument="Catalog")
     */
    private $catalog;

    /**
     * @Assert\NotNull
     * @MongoDB\ReferenceOne(targetDocument="Order")
     */
    private $order;

    /**
     * @Assert\NotNull
     * @MongoDB\ReferenceOne(targetDocument="Instance")
     */
    private $instance;

    /**
     * @Assert\NotNull
     * @MongoDB\ReferenceOne(targetDocument="BaseUser")
     */
    private $admin;

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
     * Set result
     *
     * @param string $result
     * @return self
     */
    public function setResult($result)
    {
        $this->result = $result;
        return $this;
    }

    /**
     * Get result
     *
     * @return string $result
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * Set catalog
     *
     * @param Celsius\Celsius3Bundle\Document\Catalog $catalog
     * @return self
     */
    public function setCatalog(
            \Celsius\Celsius3Bundle\Document\Catalog $catalog)
    {
        $this->catalog = $catalog;
        return $this;
    }

    /**
     * Get catalog
     *
     * @return Celsius\Celsius3Bundle\Document\Catalog $catalog
     */
    public function getCatalog()
    {
        return $this->catalog;
    }

    /**
     * Set order
     *
     * @param Celsius\Celsius3Bundle\Document\Order $order
     * @return self
     */
    public function setOrder(\Celsius\Celsius3Bundle\Document\Order $order)
    {
        $this->order = $order;
        return $this;
    }

    /**
     * Get order
     *
     * @return Celsius\Celsius3Bundle\Document\Order $order
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Set instance
     *
     * @param Celsius\Celsius3Bundle\Document\Instance $instance
     * @return self
     */
    public function setInstance(
            \Celsius\Celsius3Bundle\Document\Instance $instance)
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
     * Set date
     *
     * @param date $date
     * @return self
     */
    public function setDate($date)
    {
        $this->date = $date;
        return $this;
    }

    /**
     * Get date
     *
     * @return date $date
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set admin
     *
     * @param Celsius\Celsius3Bundle\Document\BaseUser $admin
     * @return self
     */
    public function setAdmin(\Celsius\Celsius3Bundle\Document\BaseUser $admin)
    {
        $this->admin = $admin;
        return $this;
    }

    /**
     * Get admin
     *
     * @return Celsius\Celsius3Bundle\Document\BaseUser $admin
     */
    public function getAdmin()
    {
        return $this->admin;
    }
}
