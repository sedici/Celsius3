<?php

namespace Celsius3\CoreBundle\Document;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Celsius3\CoreBundle\Helper\LifecycleHelper;
use Celsius3\CoreBundle\Document\Mixin\ReclaimableTrait;
use Celsius3\CoreBundle\Document\Mixin\CancellableTrait;
use Celsius3\CoreBundle\Document\Mixin\ProviderTrait;

/**
 * @MongoDB\Document
 */
class SingleInstanceRequest extends SingleInstance
{
    use ReclaimableTrait, CancellableTrait, ProviderTrait;

    public function applyExtraData(Request $request, array $data, LifecycleHelper $lifecycleHelper, $date)
    {
        $this->setProvider($data['extraData']['provider']);
        $this->setObservations($data['extraData']['observations']);
    }
}
