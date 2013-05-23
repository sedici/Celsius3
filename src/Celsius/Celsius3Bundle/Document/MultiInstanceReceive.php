<?php

namespace Celsius\Celsius3Bundle\Document;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Celsius\Celsius3Bundle\Helper\LifecycleHelper;
use Celsius\Celsius3Bundle\Manager\StateManager;

/**
 * @MongoDB\Document
 */
class MultiInstanceReceive extends MultiInstance
{
    /**
     * @Assert\NotBlank
     * @MongoDB\String
     */
    private $deliveryType;

    /**
     * @MongoDB\String
     */
    private $observations;

    /**
     * @Assert\NotBlank
     * @Assert\Type(type="boolean")
     * @MongoDB\Boolean
     */
    private $isReclaimed = false;

    /**
     * @MongoDB\ReferenceMany(targetDocument="File", mappedBy="event")
     */
    private $files;

    /**
     * @Assert\NotNull
     * @MongoDB\ReferenceOne(targetDocument="State", inversedBy="remoteEvents", cascade={"persist",  "refresh"})
     */
    private $remoteState;

    /**
     * @Assert\NotNull
     * @MongoDB\ReferenceOne(targetDocument="Event")
     */
    private $requestEvent;

    public function __construct()
    {
        $this->files = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function applyExtraData(Order $order, array $data,
            LifecycleHelper $lifecycleHelper, $date)
    {
        $this->setRequestEvent($data['extraData']['request']);
        $this->setObservations($data['extraData']['observations']);
        $lifecycleHelper
                ->uploadFiles($order, $this, $data['extraData']['files']);
        $this->setRemoteInstance($order->getInstance());
        $data['instance'] = $this->getRemoteInstance();
        $data['stateName'] = StateManager::STATE__APPROVAL_PENDING;
        $this
                ->setRemoteState(
                        $lifecycleHelper->getState($order, $this, $data, $this));
    }

    /**
     * Set deliveryType
     *
     * @param string $deliveryType
     * @return \MultiInstanceReceive
     */
    public function setDeliveryType($deliveryType)
    {
        $this->deliveryType = $deliveryType;
        return $this;
    }

    /**
     * Get deliveryType
     *
     * @return string $deliveryType
     */
    public function getDeliveryType()
    {
        return $this->deliveryType;
    }

    /**
     * Set observations
     *
     * @param string $observations
     * @return \MultiInstanceReceive
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
     * Add files
     *
     * @param Celsius\Celsius3Bundle\Document\File $files
     */
    public function addFile(\Celsius\Celsius3Bundle\Document\File $files)
    {
        $this->files[] = $files;
    }

    /**
     * Remove files
     *
     * @param <variableType$files
     */
    public function removeFile(\Celsius\Celsius3Bundle\Document\File $files)
    {
        $this->files->removeElement($files);
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
     * Set requestEvent
     *
     * @param Celsius\Celsius3Bundle\Document\Event $requestEvent
     * @return \MultiInstanceReceive
     */
    public function setRequestEvent(
            \Celsius\Celsius3Bundle\Document\Event $requestEvent)
    {
        $this->requestEvent = $requestEvent;
        return $this;
    }

    /**
     * Get requestEvent
     *
     * @return Celsius\Celsius3Bundle\Document\Event $requestEvent
     */
    public function getRequestEvent()
    {
        return $this->requestEvent;
    }

    /**
     * Set remoteState
     *
     * @param Celsius\Celsius3Bundle\Document\State $remoteState
     * @return \MultiInstanceReceive
     */
    public function setRemoteState(
            \Celsius\Celsius3Bundle\Document\State $remoteState)
    {
        $this->remoteState = $remoteState;
        return $this;
    }

    /**
     * Get remoteState
     *
     * @return Celsius\Celsius3Bundle\Document\State $remoteState
     */
    public function getRemoteState()
    {
        return $this->remoteState;
    }

    /**
     * Set isReclaimed
     *
     * @param boolean $isReclaimed
     * @return self
     */
    public function setIsReclaimed($isReclaimed)
    {
        $this->isReclaimed = $isReclaimed;
        return $this;
    }

    /**
     * Get isReclaimed
     *
     * @return boolean $isReclaimed
     */
    public function getIsReclaimed()
    {
        return $this->isReclaimed;
    }
}
