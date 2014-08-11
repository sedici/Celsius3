<?php

namespace Celsius3\CoreBundle\Document\Event;

use Celsius3\CoreBundle\Helper\LifecycleHelper;
use Celsius3\CoreBundle\Document\Request;

interface EventInterface
{
    public function applyExtraData(Request $request, array $data, LifecycleHelper $lifecycleHelper, $date);
}