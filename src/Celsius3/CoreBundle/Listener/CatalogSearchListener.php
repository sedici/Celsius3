<?php

namespace Celsius3\CoreBundle\Listener;

use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Celsius3\CoreBundle\Document\CatalogSearch;

class CatalogSearchListener
{

    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $document = $args->getDocument();
        $dm = $args->getDocumentManager();

        if ($document instanceof CatalogSearch && $document->getRequest()->getSearches()->count() == 0) {
            $this->container->get('celsius3_core.lifecycle_helper')->createEvent('search', $document->getRequest());
        }
    }

}
