<?php

namespace Celsius\Celsius3Bundle\Listener;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use Celsius\Celsius3Bundle\Document\Order;
use Celsius\Celsius3Bundle\Helper\LifecycleHelper;

class OrderListener
{

    public function postPersist(LifecycleEventArgs $args)
    {
        $document = $args->getDocument();
        $dm = $args->getDocumentManager();

        if ($document instanceof Order)
        {
            $lh = new LifecycleHelper($dm);
            $lh->creation($document->getId());
        }
    }

}