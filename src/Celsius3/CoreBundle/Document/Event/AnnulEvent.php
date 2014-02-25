<?php

namespace Celsius3\CoreBundle\Document\Event;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Celsius3\CoreBundle\Helper\LifecycleHelper;
use Celsius3\CoreBundle\Document\Request;

/**
 * @MongoDB\Document
 */
class AnnulEvent extends SingleInstanceEvent
{

    public function applyExtraData(Request $request, array $data, LifecycleHelper $lifecycleHelper, $date)
    {
        if (array_key_exists('request', $data['extraData'])) {
            $data['extraData']['request']->setIsAnnulled(true);
            $lifecycleHelper->refresh($data['extraData']['request']);
        }
    }

}
