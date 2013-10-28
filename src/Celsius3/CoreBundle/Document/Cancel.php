<?php

namespace Celsius3\CoreBundle\Document;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Celsius3\CoreBundle\Helper\LifecycleHelper;

/**
 * @MongoDB\Document
 */
class Cancel extends SingleInstance
{

    public function applyExtraData(Request $request, array $data, LifecycleHelper $lifecycleHelper, $date)
    {
        $data['extraData']['remoterequest']->setIsCancelled(true);
        $lifecycleHelper->refresh($data['extraData']['remoterequest']);
        $lifecycleHelper->getEventManager()->cancelRequests($data['extraData']['sirequests'], $data['extraData']['httprequest']);

        $lifecycleHelper->getEventManager()->cancelRequests($data['extraData']['mirequests'], $data['extraData']['httprequest']);
    }

}
