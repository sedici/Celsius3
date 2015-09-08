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
use Celsius3\CoreBundle\Entity\Event\SearchEvent;
use Celsius3\CoreBundle\Entity\JournalType;
use Celsius3\CoreBundle\Entity\CatalogResult;
use Celsius3\CoreBundle\Manager\CatalogManager;

class SearchEventListener
{
    private $negative = array(
        CatalogManager::CATALOG__NOT_FOUND,
        CatalogManager::CATALOG__NON_SEARCHED,
    );
    private $positive = array(
        CatalogManager::CATALOG__FOUND,
        CatalogManager::CATALOG__PARTIALLY_FOUND,
    );
    private $result = null;

    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $em = $args->getEntityManager();

        if ($entity instanceof SearchEvent) {
            $uow = $em->getUnitOfWork();
            $changeset = $uow->getEntityChangeSet($entity);

            if ($entity->getRequest()->getOrder()->getMaterialData() instanceof JournalType) {
                if (!$entity->getRequest()->getOrder()->getMaterialData()->getJournal()) {
                    $title = $entity->getRequest()->getOrder()->getMaterialData()->getOther();
                } else {
                    $title = $entity->getRequest()->getOrder()->getMaterialData()->getJournal()->getName();
                }
            } else {
                $title = $entity->getRequest()->getOrder()->getMaterialData()->getTitle();
            }

            if (array_key_exists('result', $changeset) && $changeset['result'][0] !== $changeset['result'][1]) {
                $result = $em->getRepository('Celsius3CoreBundle:CatalogResult')
                        ->findOneBy(array(
                    'catalog' => $entity->getCatalog()->getId(),
                    'title' => $title,
                ));
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

    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $em = $args->getEntityManager();

        if ($entity instanceof SearchEvent) {
            if ($entity->getRequest()->getOrder()->getMaterialData() instanceof JournalType) {
                if (!is_null($entity->getRequest()->getOrder()->getMaterialData()->getJournal())) {
                    $title = $entity->getRequest()->getOrder()->getMaterialData()->getJournal()->getName();
                } else {
                    $title = $entity->getRequest()->getOrder()->getMaterialData()->getOther();
                }
            } else {
                $title = $entity->getRequest()->getOrder()->getMaterialData()->getTitle();
            }

            $result = $em->getRepository('Celsius3CoreBundle:CatalogResult')
                    ->findOneBy(array(
                'catalog' => $entity->getCatalog()->getId(),
                'title' => $title,
            ));

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

    public function postUpdate(LifecycleEventArgs $args, $update = false)
    {
        $entity = $args->getEntity();
        $em = $args->getEntityManager();

        if ($entity instanceof SearchEvent) {
            if ($this->result) {
                $em->persist($this->result);
                $em->flush();
            }
        }
    }
}
