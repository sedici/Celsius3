<?php

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

use Doctrine\ORM\Event\LifecycleEventArgs;
use Celsius3\Entity\Event\SearchEvent;
use Celsius3\Entity\JournalType;
use Celsius3\Entity\CatalogResult;
use Celsius3\Manager\CatalogManager;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\PostPersistEventArgs;
use Doctrine\ORM\Event\PostUpdateEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;

#[AsDoctrineListener(Events::postPersist)]
#[AsDoctrineListener(Events::postUpdate)]
#[AsDoctrineListener(Events::preUpdate)]
class SearchEventListener
{
    private $negative = [
        CatalogManager::CATALOG__NOT_FOUND,
        CatalogManager::CATALOG__NON_SEARCHED,
    ];
    private $positive = [
        CatalogManager::CATALOG__FOUND,
        CatalogManager::CATALOG__PARTIALLY_FOUND,
    ];
    private $result = null;

    public function __construct(
        protected EntityManagerInterface $entityManager
    ) { }

    public function preUpdate(PreUpdateEventArgs $args)
    {
        $entity = $args->getObject();
        $em = $this->entityManager;

        if ($entity instanceof SearchEvent) {
            $uow = $em->getUnitOfWork();
            $changeset = $uow->getEntityChangeSet($entity);

            $order = $entity->getRequest()->getOrder();
            $materialData = $order->getMaterialData();

            $title = (!$materialData instanceof JournalType)
                ? $materialData->getTitle()
                : ($materialData->getJournal() !== null
                    ? $materialData->getJournal()->getName()
                    : $materialData->getOther());

            if (array_key_exists('result', $changeset) && $changeset['result'][0] !== $changeset['result'][1]) {
                $result = $em->getRepository(CatalogResult::class)
                        ->findOneBy([
                    'catalog' => $entity->getCatalog()->getId(),
                    'title' => $title,
                ]);

                if (!$result) {
                    $result = new CatalogResult();
                    $result->setCatalog($entity->getCatalog());
                    $result->setTitle($title);

                    if ($entity->getResult() !== CatalogManager::CATALOG__NON_SEARCHED) {
                        $result->setSearches($result->getSearches() + 1);
                    }
                    if (in_array($entity->getResult(), $this->positive)) {
                        $result->setMatches($result->getMatches() + 1);
                    }

                    $em->persist($result);
                    $em->flush();
                }

                $old = $changeset['result'][0];
                $new = $changeset['result'][1];

                if (in_array($old, $this->positive) && in_array($new, $this->negative)) {
                    $result->setMatches($result->getMatches() - 1);
                    if ($new === CatalogManager::CATALOG__NON_SEARCHED) {
                        $result->setSearches($result->getSearches() - 1);
                    }
                } elseif (in_array($old, $this->negative) && in_array($new, $this->positive)) {
                    $result->setMatches($result->getMatches() + 1);
                    if ($old === CatalogManager::CATALOG__NON_SEARCHED) {
                        $result->setSearches($result->getSearches() + 1);
                    }
                } elseif (in_array($old, $this->negative) && in_array($new, $this->negative)) {
                    if ($old === CatalogManager::CATALOG__NON_SEARCHED) {
                        $result->setSearches($result->getSearches() + 1);
                    } else {
                        $result->setSearches($result->getSearches() - 1);
                    }
                }
                $this->result = $result;
            }
        }
    }

    public function postPersist(PostPersistEventArgs $args): void
    {
        $entity = $args->getObject();
        $em = $this->entityManager;

        if ($entity instanceof SearchEvent) {
            $order = $entity->getRequest()->getOrder();
            $materialData = $order->getMaterialData();

            $title = (!$materialData instanceof JournalType)
                ? $materialData->getTitle()
                : ($materialData->getJournal() !== null
                    ? $materialData->getJournal()->getName()
                    : $materialData->getOther());

            $result = $em->getRepository(CatalogResult::class)
                    ->findOneBy([
                'catalog' => $entity->getCatalog()->getId(),
                'title' => $title,
            ]);

            if (!$result) {
                $result = new CatalogResult();
                $result->setCatalog($entity->getCatalog());
                $result->setTitle($title);
            }
            if ($entity->getResult() !== CatalogManager::CATALOG__NON_SEARCHED) {
                $result->setSearches($result->getSearches() + 1);
            }
            if (in_array($entity->getResult(), $this->positive)) {
                $result->setMatches($result->getMatches() + 1);
            }

            $em->persist($result);
            $em->flush();
        }
    }

    public function postUpdate(PostUpdateEventArgs $args): void
    {
        $entity = $args->getObject();
        $em = $this->entityManager;

        if ($entity instanceof SearchEvent) {
            if ($this->result) {
                $em->persist($this->result);
                $em->flush();
            }
        }
    }
}
