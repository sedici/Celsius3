<?php

namespace Celsius3\CoreBundle\Document\Event;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Celsius3\CoreBundle\Helper\LifecycleHelper;
use Celsius3\CoreBundle\Manager\StateManager;
use Celsius3\CoreBundle\Document\Mixin\ReclaimableTrait;
use Celsius3\CoreBundle\Document\Mixin\ApprovableTrait;
use Celsius3\CoreBundle\Document\Request;

/**
 * @ODM\Document
 */
class MultiInstanceReceiveEvent extends MultiInstanceEvent
{
    use ReclaimableTrait,
        ApprovableTrait;
    /**
     * @Assert\NotBlank
     * @ODM\String
     */
    private $deliveryType;
    /**
     * @ODM\ReferenceMany(targetDocument="Celsius3\CoreBundle\Document\File", mappedBy="event")
     */
    private $files;
    /**
     * @Assert\NotNull
     * @ODM\ReferenceOne(targetDocument="Celsius3\CoreBundle\Document\State", inversedBy="remoteEvents", cascade={"persist",  "refresh"})
     */
    private $remoteState;
    /**
     * @Assert\NotNull
     * @ODM\ReferenceOne(targetDocument="Celsius3\CoreBundle\Document\Event\Event")
     */
    private $requestEvent;

    public function __construct()
    {
        $this->files = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function applyExtraData(Request $request, array $data, LifecycleHelper $lifecycleHelper, $date)
    {
        $this->setRequestEvent($data['extraData']['request']);
        $this->setObservations($data['extraData']['observations']);
        $lifecycleHelper->uploadFiles($request, $this, $data['extraData']['files']);
        $this->setRemoteInstance($request->getInstance());
        $data['instance'] = $this->getRemoteInstance();
        $data['stateName'] = StateManager::STATE__APPROVAL_PENDING;
        $this->setRemoteState($lifecycleHelper->getState($request, $this, $data, $this));
    }

    /**
     * Set deliveryType
     *
     * @param  string $deliveryType
     * @return self
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
     * Add files
     *
     * @param Celsius3\CoreBundle\Document\File $files
     */
    public function addFile(\Celsius3\CoreBundle\Document\File $files)
    {
        $this->files[] = $files;
    }

    /**
     * Remove files
     *
     * @param Celsius3\CoreBundle\Document\File $files
     */
    public function removeFile(\Celsius3\CoreBundle\Document\File $files)
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
     * Set remoteState
     *
     * @param  Celsius3\CoreBundle\Document\State $remoteState
     * @return self
     */
    public function setRemoteState(\Celsius3\CoreBundle\Document\State $remoteState)
    {
        $this->remoteState = $remoteState;

        return $this;
    }

    /**
     * Get remoteState
     *
     * @return Celsius3\CoreBundle\Document\State $remoteState
     */
    public function getRemoteState()
    {
        return $this->remoteState;
    }

    /**
     * Set requestEvent
     *
     * @param  Celsius3\CoreBundle\Document\Event\Event $requestEvent
     * @return self
     */
    public function setRequestEvent(\Celsius3\CoreBundle\Document\Event\Event $requestEvent)
    {
        $this->requestEvent = $requestEvent;

        return $this;
    }

    /**
     * Get requestEvent
     *
     * @return Celsius3\CoreBundle\Document\Event\Event $requestEvent
     */
    public function getRequestEvent()
    {
        return $this->requestEvent;
    }
}