<?php

namespace Celsius\Celsius3Bundle\Listener;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use Celsius\Celsius3Bundle\Document\Counter;
use Celsius\Celsius3Bundle\Document\Instance;
use Celsius\Celsius3Bundle\Document\Order;

class CounterListener
{

    public function prePersist(LifecycleEventArgs $args)
    {
        $document = $args->getDocument();
        $dm = $args->getDocumentManager();

        if ($document instanceof Order)
        {
            $document->setCode($dm->createQueryBuilder('CelsiusCelsius3Bundle:Counter')
                            ->findAndUpdate()
                            ->refresh(true)
                            ->field('name')->equals($document->getInstance()->getId())
                            ->field('value')->inc(1)
                            ->getQuery()
                            ->execute()
                            ->getValue()
            );
        }
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $document = $args->getDocument();
        $dm = $args->getDocumentManager();

        if ($document instanceof Instance)
        {
            $counter = new Counter();
            $counter->setName($document->getId());
            $counter->setValue(1);
            $dm->persist($counter);
            $dm->flush();
        }
    }

}
