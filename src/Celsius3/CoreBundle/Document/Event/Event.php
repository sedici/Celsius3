<?php

namespace Celsius3\CoreBundle\Document\Event;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Celsius3\CoreBundle\Helper\LifecycleHelper;
use Celsius3\CoreBundle\Document\Request;

/**
 * @MongoDB\Document
 * @MongoDB\Document(repositoryClass="Celsius3\CoreBundle\Repository\EventRepository")
 * @MongoDB\InheritanceType("SINGLE_COLLECTION")
 * @MongoDB\DiscriminatorField(fieldName="type")
 * @MongoDB\DiscriminatorMap({
 *   "creation"="CreationEvent",
 *   "search"="SearchEvent",
 *   "sirequest"="SingleInstanceRequestEvent",
 *   "cancel"="CancelEvent",
 *   "annul"="AnnulEvent",
 *   "sireceive"="SingleInstanceReceiveEvent",
 *   "mireceive"="MultiInstanceReceiveEvent",
 *   "mirequest"="MultiInstanceRequestEvent",
 *   "deliver"="DeliverEvent",
 *   "localcancel"="LocalCancelEvent",
 *   "remotecancel"="RemoteCancelEvent",
 *   "reclaim"="ReclaimEvent",
 *   "approve"="ApproveEvent",
 *   "undo"="UndoEvent",
 *   "si"="SingleInstanceEvent",
 *   "mi"="MultiInstanceEvent",
 *   "take"="TakeEvent",
 *   "upload"="UploadEvent",
 *   "reupload"="ReuploadEvent"
 * })
 */
class Event implements EventInterface
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
     * @Assert\NotNull
     * @MongoDB\ReferenceOne(targetDocument="Celsius3\CoreBundle\Document\Request", inversedBy="events")
     */
    private $request;

    /**
     * @MongoDB\ReferenceOne(targetDocument="Celsius3\CoreBundle\Document\BaseUser", inversedBy="events")
     */
    private $operator;

    /**
     * @Assert\NotNull
     * @MongoDB\ReferenceOne(targetDocument="Celsius3\CoreBundle\Document\State", inversedBy="events", cascade={"persist", "refresh"})
     */
    private $state;

    /**
     * @Assert\NotNull
     * @MongoDB\ReferenceOne(targetDocument="Celsius3\CoreBundle\Document\Instance", inversedBy="events")
     */
    private $instance;

    public function applyExtraData(Request $request, array $data, LifecycleHelper $lifecycleHelper, $date)
    {

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
     * Set date
     *
     * @param  date $date
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
     * Set observations
     *
     * @param  string $observations
     * @return self
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
     * Set operator
     *
     * @param  Celsius3\CoreBundle\Document\BaseUser $operator
     * @return self
     */
    public function setOperator(\Celsius3\CoreBundle\Document\BaseUser $operator = null)
    {
        $this->operator = $operator;

        return $this;
    }

    /**
     * Get operator
     *
     * @return Celsius3\CoreBundle\Document\BaseUser $operator
     */
    public function getOperator()
    {
        return $this->operator;
    }

    /**
     * Set state
     *
     * @param  Celsius3\CoreBundle\Document\State $state
     * @return self
     */
    public function setState(\Celsius3\CoreBundle\Document\State $state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return Celsius3\CoreBundle\Document\State $state
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set instance
     *
     * @param  Celsius3\CoreBundle\Document\Instance $instance
     * @return self
     */
    public function setInstance(\Celsius3\CoreBundle\Document\Instance $instance)
    {
        $this->instance = $instance;

        return $this;
    }

    /**
     * Get instance
     *
     * @return Celsius3\CoreBundle\Document\Instance $instance
     */
    public function getInstance()
    {
        return $this->instance;
    }

    /**
     * Set request
     *
     * @param  Celsius3\CoreBundle\Document\Request $request
     * @return self
     */
    public function setRequest(\Celsius3\CoreBundle\Document\Request $request)
    {
        $this->request = $request;

        return $this;
    }

    /**
     * Get request
     *
     * @return Celsius3\CoreBundle\Document\Request $request
     */
    public function getRequest()
    {
        return $this->request;
    }

}
