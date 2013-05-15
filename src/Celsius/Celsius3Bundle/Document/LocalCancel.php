<?php

namespace Celsius\Celsius3Bundle\Document;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Celsius\Celsius3Bundle\Helper\LifecycleHelper;

/**
 * @MongoDB\Document
 */
class LocalCancel extends MultiInstance
{
    public function applyExtraData(Order $order, array $data,
            LifecycleHelper $lifecycleHelper, $date)
    {
        $data['extraData']['request']->setIsCancelled(true);
        $lifecycleHelper->refresh($data['extraData']['request']);
    }
}
