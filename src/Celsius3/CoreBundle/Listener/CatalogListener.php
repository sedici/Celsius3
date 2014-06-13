<?php

namespace Celsius3\CoreBundle\Listener;

use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use Celsius3\CoreBundle\Document\Catalog;
use Celsius3\CoreBundle\Document\CatalogPosition;
use Celsius3\CoreBundle\Document\Instance;
use Symfony\Component\DependencyInjection\ContainerInterface;

class CatalogListener
{

    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $document = $args->getDocument();
        $dm = $args->getDocumentManager();

        if ($document instanceof Catalog) {
            $directory = $this->container->get('celsius3_core.instance_manager')
                    ->getDirectory();
            if ($document->getInstance()->getId() == $directory->getId()) {
                $instances = $dm->getRepository('Celsius3CoreBundle:Instance')
                        ->findAllExceptDirectory()
                        ->getQuery()
                        ->execute();
                foreach ($instances as $instance) {
                    $place = count($dm->getRepository('Celsius3CoreBundle:CatalogPosition')
                                    ->findBy(array(
                                        'instance.id' => $instance->getId(),
                    )));

                    $position = new CatalogPosition();
                    $position->setCatalog($document);
                    $position->setInstance($instance);
                    $position->setPosition($place);
                    $dm->persist($position);
                }
                $dm->flush();
            } else {
                $place = count($dm->getRepository('Celsius3CoreBundle:CatalogPosition')
                                ->findBy(array(
                                    'instance.id' => $document->getInstance()->getId(),
                )));

                $position = new CatalogPosition();
                $position->setCatalog($document);
                $position->setInstance($document->getInstance());
                $position->setPosition($place);
                $dm->persist($position);
                $dm->flush();
            }
        } elseif ($document instanceof Instance) {
            $catalogs = $dm->getRepository('Celsius3CoreBundle:Catalog')
                    ->findBy(array(
                'instance.id' => $this->container->get('celsius3_core.instance_manager')->getDirectory()->getId(),
            ));
            
            $place = 0;
            foreach ($catalogs as $catalog) {
                $position = new CatalogPosition();
                $position->setCatalog($catalog);
                $position->setInstance($document);
                $position->setPosition($place++);
                $dm->persist($position);
            }
            $dm->flush();
        }
    }

}
