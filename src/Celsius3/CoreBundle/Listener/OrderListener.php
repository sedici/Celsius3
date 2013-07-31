<?php

namespace Celsius3\CoreBundle\Listener;

use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Celsius3\CoreBundle\Document\Order;
use Celsius3\CoreBundle\Manager\EventManager;

class OrderListener
{

    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $document = $args->getDocument();

        if ($document instanceof Order) {
            $document->setIsLiblink($document->getInstance()->getIsLiblink());
        }
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $document = $args->getDocument();

        if ($document instanceof Order) {
            $this->container->get('celsius3_core.lifecycle_helper')->createEvent(EventManager::EVENT__CREATION, $document, $document->getInstance());
        }
    }

}
