<?php

namespace Celsius\Celsius3Bundle\Document;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Celsius\Celsius3Bundle\Helper\LifecycleHelper;

/**
 * @MongoDB\Document
 */
class SingleInstanceReceive extends SingleInstance
{

    /**
     * @Assert\NotBlank()
     * @MongoDB\String
     */
    private $deliveryType;

    /**
     * @MongoDB\String
     */
    private $observations;

    /**
     * @Assert\NotBlank()
     * @Assert\Type(type="boolean")
     * @MongoDB\Boolean
     */
    private $reclaimed = false;

    /**
     * @MongoDB\ReferenceMany(targetDocument="File", mappedBy="event")
     */
    private $files;

    /**
     * @MongoDB\ReferenceOne(targetDocument="Event")
     */
    private $requestEvent;

    /**
     * Set requestEvent
     *
     * @param Celsius\Celsius3Bundle\Document\Event $requestEvent
     * @return \Receive
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

    public function __construct()
    {
        $this->files = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set deliveryType
     *
     * @param string $deliveryType
     * @return \Receive
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
     * @return \Receive
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
    public function addFiles(\Celsius\Celsius3Bundle\Document\File $files)
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
     * Set reclaimed
     *
     * @param boolean $reclaimed
     * @return \Receive
     */
    public function setReclaimed($reclaimed)
    {
        $this->reclaimed = $reclaimed;
        return $this;
    }

    /**
     * Get reclaimed
     *
     * @return boolean $reclaimed
     */
    public function getReclaimed()
    {
        return $this->reclaimed;
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

    public function applyExtraData(Order $order, array $extraData,
            LifecycleHelper $lifecycleHelper, $date)
    {
        $this->setRequestEvent($extraData['request']);
        $this->setObservations($extraData['observations']);
        $lifecycleHelper->uploadFiles($order, $this, $extraData['files']);
    }
}
