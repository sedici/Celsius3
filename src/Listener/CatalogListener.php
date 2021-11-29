<?php

declare(strict_types=1);

/*
 * Celsius3 - Order management
 * Copyright (C) 2014 PREBI-SEDICI <info@prebi.unlp.edu.ar> http://prebi.unlp.edu.ar http://sedici.unlp.edu.ar
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

namespace Celsius3\Listener;

use Celsius3\Entity\Catalog;
use Celsius3\Entity\CatalogPosition;
use Celsius3\Entity\Instance;
use Celsius3\Manager\InstanceManager;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class CatalogListener
{
    private $requestStack;
    private $instanceManager;

    public function __construct(RequestStack $requestStack, InstanceManager $instanceManager)
    {
        $this->requestStack = $requestStack;
        $this->instanceManager = $instanceManager;
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        $request = $this->requestStack->getCurrentRequest();
        $em = $args->getEntityManager();

        if ($entity instanceof Catalog) {
            if (array_key_exists('enable', $request->request->all()['catalog'])) {
                $enable = $request->request->all()['catalog']['enable'];
            } else {
                $enable = false;
            }

            $directory = $this->instanceManager->getDirectory();
            if ($entity->getInstance()->getId() == $directory->getId()) {
                $instances = $em->getRepository(Instance::class)
                    ->findAllExceptDirectory()
                    ->getQuery()
                    ->execute();
                foreach ($instances as $instance) {
                    $place = count(
                        $em->getRepository(CatalogPosition::class)
                            ->findBy(array(
                                         'instance' => $instance->getId(),
                                     ))
                    );

                    $position = new CatalogPosition();
                    $position->setCatalog($entity);
                    $position->setInstance($instance);
                    $position->setPosition($place);
                    $position->setEnabled($enable);
                    $em->persist($position);
                }
                $em->flush();
            } else {
                $place = count(
                    $em->getRepository(CatalogPosition::class)
                        ->findBy(array(
                                     'instance' => $entity->getInstance()->getId(),
                                 ))
                );

                $position = new CatalogPosition();
                $position->setCatalog($entity);
                $position->setInstance($entity->getInstance());
                $position->setPosition($place);
                $position->setEnabled($enable);
                $em->persist($position);
                $em->flush();
            }
        } elseif ($entity instanceof Instance) {
            $catalogs = $em->getRepository(Catalog::class)
                ->findBy(array(
                             'instance' => $this->instanceManager->getDirectory()->getId(),
                         ));

            $place = 0;
            foreach ($catalogs as $catalog) {
                $position = new CatalogPosition();
                $position->setCatalog($catalog);
                $position->setInstance($entity);
                $position->setPosition($place++);
                $position->setEnabled(true);
                $em->persist($position);
            }
            $em->flush();
        }
    }
}
