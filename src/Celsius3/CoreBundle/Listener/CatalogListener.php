<?php
/*
 * Celsius3 - Order management
 * Copyright (C) 2014 PrEBi <info@prebi.unlp.edu.ar>
 *
 * This file is part of Celsius3.
 *
 * Celsius3 is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Celsius3 is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Celsius3.  If not, see <http://www.gnu.org/licenses/>.
 */

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