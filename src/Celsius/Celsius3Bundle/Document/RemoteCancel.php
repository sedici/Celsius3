<?php

namespace Celsius\Celsius3Bundle\Document;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Celsius\Celsius3Bundle\Helper\LifecycleHelper;
use Celsius\Celsius3Bundle\Manager\StateManager;
use Celsius\Celsius3Bundle\Manager\EventManager;

/**
 * @MongoDB\Document
 */
class RemoteCancel extends MultiInstance
{
    public function applyExtraData(Order $order, array $data,
            LifecycleHelper $lifecycleHelper, $date)
    {
        $data['extraData']['request']->setIsCancelled(true);
        $lifecycleHelper->refresh($data['extraData']['request']);
        $this
                ->setRemoteInstance(
                        $data['extraData']['request']->getRemoteInstance());
        $lifecycleHelper
                ->createEvent(EventManager::EVENT__CANCEL, $order,
                        $this->getRemoteInstance());
    }
}
