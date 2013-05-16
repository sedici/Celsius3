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
        foreach ($data['extraData']['sirequests'] as $request) {
            $data['extraData']['httprequest']->query
                    ->set('request', $request->getId());
            $lifecycleHelper->createEvent(EventManager::EVENT__CANCEL, $order);
        }

        foreach ($data['extraData']['mirequests'] as $request) {
            $data['extraData']['httprequest']->query
                    ->set('request', $request->getId());
            $lifecycleHelper->createEvent(EventManager::EVENT__CANCEL, $order);
        }
    }
}
