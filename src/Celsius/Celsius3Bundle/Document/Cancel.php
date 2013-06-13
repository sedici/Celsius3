<?php

namespace Celsius\Celsius3Bundle\Document;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Celsius\Celsius3Bundle\Helper\LifecycleHelper;
use Celsius\Celsius3Bundle\Manager\EventManager;

/**
 * @MongoDB\Document
 */
class Cancel extends SingleInstance
{
    public function applyExtraData(Order $order, array $data,
            LifecycleHelper $lifecycleHelper, $date)
    {
        $data['extraData']['remoterequest']->setIsCancelled(true);
        $lifecycleHelper->refresh($data['extraData']['remoterequest']);
        $lifecycleHelper->getEventManager()
                ->cancelRequests($data['extraData']['sirequests'],
                        $data['extraData']['httprequest']);

        $lifecycleHelper->getEventManager()
                ->cancelRequests($data['extraData']['mirequests'],
                        $data['extraData']['httprequest']);
    }
}
