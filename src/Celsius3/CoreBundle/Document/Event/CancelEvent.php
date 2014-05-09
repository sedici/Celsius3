<?php

namespace Celsius3\CoreBundle\Document\Event;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Celsius3\CoreBundle\Helper\LifecycleHelper;
use Celsius3\CoreBundle\Document\Request;

/**
 * @MongoDB\Document
 */
class CancelEvent extends SingleInstanceEvent
{

    public function applyExtraData(Request $request, array $data, LifecycleHelper $lifecycleHelper, $date)
    {
        if (array_key_exists('remoterequest', $data['extraData'])) {
            $data['extraData']['remoterequest']->setIsCancelled(true);
            $lifecycleHelper->refresh($data['extraData']['remoterequest']);
        }
        $lifecycleHelper->getEventManager()->cancelRequests($data['extraData']['sirequests'], $data['extraData']['httprequest']);
        $lifecycleHelper->getEventManager()->cancelRequests($data['extraData']['mirequests'], $data['extraData']['httprequest']);
    }

}
