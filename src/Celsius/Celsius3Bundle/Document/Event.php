<?php

namespace Celsius\Celsius3Bundle\Document;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document
 * @MongoDB\InheritanceType("SINGLE_COLLECTION")
 * @MongoDB\DiscriminatorField(fieldName="type")
 * @MongoDB\DiscriminatorMap({
 *   "creation"="Creation",
 *   "search"="Search",
 *   "sirequest"="SingleInstanceRequest",
 *   "sideliver"="SingleInstanceDeliver",
 *   "cancel"="Cancel",
 *   "annul"="Annul",
 *   "mirequest"="MultiInstanceRequest",
 *   "mideliver"="MultiInstanceDeliver",
 *   "localcancel"="LocalCancel",
 *   "remotecancel"="RemoteCancel",
 *   "reclaim"="Reclaim",
 *   "approve"="Approve"
 * })
 */
class Event
{

    /**
     * @MongoDB\Id
     */
    private $id;

    /**
     * @Assert\Date()
     * @MongoDB\Date
     */
    private $date;

    /**
     * @MongoDB\String
     */
    private $observations;

    /**
     * @MongoDB\ReferenceOne(targetDocument="Order", inversedBy="events")
     */
    private $order;

    /**
     * @MongoDB\ReferenceOne(targetDocument="BaseUser", inversedBy="events")
     */
    private $operator;

    /**
     * @MongoDB\ReferenceOne(targetDocument="State", inversedBy="events")
     */
    private $state;

    /**
     * @MongoDB\ReferenceOne(targetDocument="Instance", inversedBy="events")
     */
    private $instance;

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
     * Set date
     *
     * @param date $date
     * @return \Event
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
     * Set observations
     *
     * @param string $observations
     * @return \Event
     */
    public function setObservations($observations)
    {
        $this->observations = $observations;
        return $this;
    }

    /**
     * Get observations
     *
     * @return string $observations
     */
    public function getObservations()
    {
        return $this->observations;
    }

    /**
     * Set order
     *
     * @param Celsius\Celsius3Bundle\Document\Order $order
     * @return \Event
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
     * Set operator
     *
     * @param Celsius\Celsius3Bundle\Document\BaseUser $operator
     * @return \Event
     */
    public function setOperator(\Celsius\Celsius3Bundle\Document\BaseUser $operator = null)
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
     * Set state
     *
     * @param Celsius\Celsius3Bundle\Document\State $state
     * @return \Event
     */
    public function setState(\Celsius\Celsius3Bundle\Document\State $state)
    {
        $this->state = $state;
        return $this;
    }

    /**
     * Get state
     *
     * @return Celsius\Celsius3Bundle\Document\State $state
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set instance
     *
     * @param Celsius\Celsius3Bundle\Document\Instance $instance
     * @return \Event
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

}
