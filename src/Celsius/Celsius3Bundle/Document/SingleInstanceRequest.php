<?php

namespace Celsius\Celsius3Bundle\Document;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Celsius\Celsius3Bundle\Helper\LifecycleHelper;
use Celsius\Celsius3Bundle\Document\Mixin\ReclaimableTrait;
use Celsius\Celsius3Bundle\Document\Mixin\CancellableTrait;
use Celsius\Celsius3Bundle\Document\Mixin\ProviderTrait;

/**
 * @MongoDB\Document
 */
class SingleInstanceRequest extends SingleInstance
{
    use ReclaimableTrait, CancellableTrait, ProviderTrait;

    public function applyExtraData(Order $order, array $data,
            LifecycleHelper $lifecycleHelper, $date)
    {
        $this->setProvider($data['extraData']['provider']);
        $this->setObservations($data['extraData']['observations']);
    }
}