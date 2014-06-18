<?php

namespace Celsius3\CoreBundle\Document\Event;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Celsius3\CoreBundle\Helper\LifecycleHelper;
use Celsius3\CoreBundle\Document\Request;
use Celsius3\CoreBundle\Document\Event\UploadEvent;

/**
 * @MongoDB\Document
 */
class ReclaimEvent extends SingleInstanceEvent
{

    /**
     * @Assert\NotNull
     * @MongoDB\ReferenceOne(targetDocument="Celsius3\CoreBundle\Document\Event\Event")
     */
    private $requestEvent;

    /**
     * @MongoDB\ReferenceOne(targetDocument="Celsius3\CoreBundle\Document\Event\Event")
     */
    private $receiveEvent;

    public function applyExtraData(Request $request, array $data, LifecycleHelper $lifecycleHelper, $date)
    {
        if (array_key_exists('request', $data['extraData'])) {
            $this->setRequestEvent($data['extraData']['request']);
        } else {
            $this->setReceiveEvent($data['extraData']['receive']);
            if (!($data['extraData']['receive'] instanceof UploadEvent)) {
                $this->setRequestEvent($this->getReceiveEvent()->getRequestEvent());
            }
        }
        $this->setObservations($data['extraData']['observations']);
    }

    /**
     * Set requestEvent
     *
     * @param Celsius3\CoreBundle\Document\Event\Event $requestEvent
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

    /**
     * Set receiveEvent
     *
     * @param Celsius3\CoreBundle\Document\Event\Event $receiveEvent
     * @return self
     */
    public function setReceiveEvent(\Celsius3\CoreBundle\Document\Event\Event $receiveEvent)
    {
        $this->receiveEvent = $receiveEvent;
        return $this;
    }

    /**
     * Get receiveEvent
     *
     * @return Celsius3\CoreBundle\Document\Event\Event $receiveEvent
     */
    public function getReceiveEvent()
    {
        return $this->receiveEvent;
    }

}
