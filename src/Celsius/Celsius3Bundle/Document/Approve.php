<?php

namespace Celsius\Celsius3Bundle\Document;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Celsius\Celsius3Bundle\Helper\LifecycleHelper;

/**
 * @MongoDB\Document
 */
class Approve extends MultiInstance
{

    /**
     * @MongoDB\ReferenceOne(targetDocument="Event")
     */
    private $receiveEvent;

    /**
     * Set receiveEvent
     *
     * @param Celsius\Celsius3Bundle\Document\Event $receiveEvent
     * @return \Approve
     */
    public function setReceiveEvent(
            \Celsius\Celsius3Bundle\Document\Event $receiveEvent)
    {
        $this->receiveEvent = $receiveEvent;
        return $this;
    }

    /**
     * Get receiveEvent
     *
     * @return Celsius\Celsius3Bundle\Document\Event $receiveEvent
     */
    public function getReceiveEvent()
    {
        return $this->receiveEvent;
    }

    public function applyExtraData(Order $order, array $extraData,
            LifecycleHelper $lifecycleHelper, $date)
    {
        $this->setReceiveEvent($extraData['receive']);
    }
}
