<?php

namespace Celsius3\CoreBundle\Listener;

use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use Celsius3\CoreBundle\Document\Counter;
use Celsius3\CoreBundle\Document\Instance;
use Celsius3\CoreBundle\Document\Order;

class CounterListener
{

    public function prePersist(LifecycleEventArgs $args)
    {
        $document = $args->getDocument();
        $dm = $args->getDocumentManager();

        if ($document instanceof Order) {
            $document->setCode($dm->createQueryBuilder('Celsius3CoreBundle:Counter')
                            ->findAndUpdate()->refresh(true)
                            ->field('name')
                            ->equals($document->getOriginalRequest()->getInstance()->getId())
                            ->field('value')->inc(1)->getQuery()
                            ->execute()->getValue());
        }
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $document = $args->getDocument();
        $dm = $args->getDocumentManager();

        if ($document instanceof Instance) {
            $counter = new Counter();
            $counter->setName($document->getId());
            $counter->setValue(1);
            $dm->persist($counter);
            $dm->flush();
        }
    }

}
