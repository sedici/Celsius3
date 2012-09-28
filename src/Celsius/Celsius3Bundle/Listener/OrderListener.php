<?php

namespace Celsius\Celsius3Bundle\Listener;

use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use Celsius\Celsius3Bundle\Document\Order;
use Celsius\Celsius3Bundle\Helper\LifecycleHelper;

class OrderListener
{

    private $lh = null;

    public function postPersist(LifecycleEventArgs $args)
    {
        if (is_null($this->lh))
        {
            $this->lh = new LifecycleHelper($args->getDocumentManager());
        }

        $document = $args->getDocument();

        if ($document instanceof Order)
        {
            $this->lh->createEvent('creation', $document);
        }
    }

}