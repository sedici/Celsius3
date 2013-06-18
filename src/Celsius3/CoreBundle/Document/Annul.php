<?php

namespace Celsius3\CoreBundle\Document;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Celsius3\CoreBundle\Helper\LifecycleHelper;

/**
 * @MongoDB\Document
 */
class Annul extends SingleInstance
{
    public function applyExtraData(Order $order, array $data,
            LifecycleHelper $lifecycleHelper, $date)
    {
        if (array_key_exists('request', $data['extraData'])) {
            $data['extraData']['request']->setIsAnnulled(true);
            $lifecycleHelper->refresh($data['extraData']['request']);
        }
    }
}
