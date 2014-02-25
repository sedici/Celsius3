<?php

namespace Celsius3\CoreBundle\Document\Event;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Celsius3\CoreBundle\Helper\LifecycleHelper;
use Celsius3\CoreBundle\Document\Request;

/**
 * @MongoDB\Document
 */
class ApproveEvent extends MultiInstanceEvent
{

    /**
     * @Assert\NotNull
     * @MongoDB\ReferenceOne(targetDocument="Event")
     */
    private $receiveEvent;

    public function applyExtraData(Request $request, array $data, LifecycleHelper $lifecycleHelper, $date)
    {
        $this->setReceiveEvent($data['extraData']['receive']);
    }

    /**
     * Set receiveEvent
     *
     * @param Celsius3\CoreBundle\Document\Event $receiveEvent
     * @return self
     */
    public function setReceiveEvent(\Celsius3\CoreBundle\Document\Event $receiveEvent)
    {
        $this->receiveEvent = $receiveEvent;
        return $this;
    }

    /**
     * Get receiveEvent
     *
     * @return Celsius3\CoreBundle\Document\Event $receiveEvent
     */
    public function getReceiveEvent()
    {
        return $this->receiveEvent;
    }

}
