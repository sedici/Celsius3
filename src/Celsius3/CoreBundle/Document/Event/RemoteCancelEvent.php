<?php

namespace Celsius3\CoreBundle\Document\Event;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Celsius3\CoreBundle\Helper\LifecycleHelper;
use Celsius3\CoreBundle\Manager\EventManager;
use Celsius3\CoreBundle\Document\Request;

/**
 * @MongoDB\Document
 */
class RemoteCancelEvent extends MultiInstanceEvent
{

    public function applyExtraData(Request $request, array $data, LifecycleHelper $lifecycleHelper, $date)
    {
        $data['extraData']['request']->setIsCancelled(true);
        $lifecycleHelper->refresh($data['extraData']['request']);
        $this->setRemoteInstance($data['extraData']['request']->getRemoteInstance());
        $lifecycleHelper->createEvent(EventManager::EVENT__CANCEL, $request, $this->getRemoteInstance());
    }

}
