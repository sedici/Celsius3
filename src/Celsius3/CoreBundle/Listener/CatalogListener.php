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

use Doctrine\ORM\Event\LifecycleEventArgs;
use Celsius3\CoreBundle\Entity\Catalog;
use Celsius3\CoreBundle\Entity\CatalogPosition;
use Celsius3\CoreBundle\Entity\Instance;
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
        $entity = $args->getEntity();
        $em = $args->getEntityManager();

        if ($entity instanceof Catalog) {
            $directory = $this->container->get('celsius3_core.instance_manager')
                    ->getDirectory();
            if ($entity->getInstance()->getId() == $directory->getId()) {
                $instances = $em->getRepository('Celsius3CoreBundle:Instance')
                        ->findAllExceptDirectory()
                        ->getQuery()
                        ->execute();
                foreach ($instances as $instance) {
                    $place = count($em->getRepository('Celsius3CoreBundle:CatalogPosition')
                                    ->findBy(array(
                                        'instance' => $instance->getId(),
                    )));

                    $position = new CatalogPosition();
                    $position->setCatalog($entity);
                    $position->setInstance($instance);
                    $position->setPosition($place);
                    $em->persist($position);
                }
                $em->flush();
            } else {
                $place = count($em->getRepository('Celsius3CoreBundle:CatalogPosition')
                                ->findBy(array(
                                    'instance' => $entity->getInstance()->getId(),
                )));

                $position = new CatalogPosition();
                $position->setCatalog($entity);
                $position->setInstance($entity->getInstance());
                $position->setPosition($place);
                $em->persist($position);
                $em->flush();
            }
        } elseif ($entity instanceof Instance) {
            $catalogs = $em->getRepository('Celsius3CoreBundle:Catalog')
                    ->findBy(array(
                'instance' => $this->container->get('celsius3_core.instance_manager')->getDirectory()->getId(),
            ));

            $place = 0;
            foreach ($catalogs as $catalog) {
                $position = new CatalogPosition();
                $position->setCatalog($catalog);
                $position->setInstance($entity);
                $position->setPosition($place++);
                $em->persist($position);
            }
            $em->flush();
        }
    }
}
