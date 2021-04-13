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

namespace Celsius3\CoreBundle\Repository;

use Celsius3\CoreBundle\Entity\Event\Event;
use Celsius3\CoreBundle\Entity\Event\MultiInstanceReceiveEvent;
use Celsius3\CoreBundle\Entity\Event\MultiInstanceRequestEvent;
use Celsius3\CoreBundle\Entity\Event\SearchEvent;
use Celsius3\CoreBundle\Entity\Event\SingleInstanceReceiveEvent;
use Celsius3\CoreBundle\Entity\Event\SingleInstanceRequestEvent;
use Celsius3\CoreBundle\Entity\Instance;
use Celsius3\CoreBundle\Entity\Institution;
use Celsius3\CoreBundle\Entity\Journal;
use Celsius3\CoreBundle\Entity\JournalType;
use Celsius3\CoreBundle\Entity\Order;
use Celsius3\CoreBundle\Manager\CatalogManager;
use Doctrine\ORM\Query\Expr\Join;

/**
 * EventRepository.
 */
class EventRepository extends BaseRepository
{
    private function generalSearch()
    {
        $qb = $this->createQueryBuilder('e');

        $qb->select('s')
            ->addSelect('c')
            ->addSelect('r')
            ->addSelect('o')
            ->addSelect('md')
            ->innerJoin(SearchEvent::class, 's', Join::WITH, 'e = s')
            ->innerJoin('s.request', 'r')
            ->innerJoin('r.order', 'o')
            ->innerJoin('o.materialData', 'md')
            ->innerJoin(JournalType::class, 'jt', Join::WITH, 'md = jt')
            ->innerJoin('jt.journal', 'j')
            ->innerJoin('s.catalog', 'c');

        return $qb;
    }

    public function findSimilarSearches(Order $order, Instance $instance)
    {
        if ($order->getMaterialData() instanceof JournalType) {
            $qb = $this->generalSearch();

            $qb->where('jt.journal IS NOT NULL')
                ->andWhere('s.instance = :instance')->setParameter('instance', $instance->getId())
                ->andWhere('s.result IN (:result)')->setParameter('result', [CatalogManager::CATALOG__FOUND, CatalogManager::CATALOG__PARTIALLY_FOUND])
                ->andWhere('o.id <> :order_id')->setParameter('order_id', $order->getId());

            $qb->orderBy('s.result', 'ASC')
                ->addOrderBy('s.createdAt', 'DESC');

            $qb->andWhere('j.name = :name');

            if (is_null($order->getMaterialData()->getJournal())) {
                $qb = $qb->setParameter('name', $order->getMaterialData()->getOther());
            } else {
                $qb = $qb->setParameter('name', $order->getMaterialData()->getJournal()->getName());
            }
        } else {
            $qb = $this->getEntityManager()->getRepository('Celsius3CoreBundle:Event\\SearchEvent')
                ->createQueryBuilder('s')
                ->select('s, c, r, o')
                ->join('s.catalog', 'c')
                ->join('s.request', 'r')
                ->join('r.order', 'o')
                ->join('o.materialData', 'md')
                ->where('md.title = :name')
                ->andWhere('o.id <> :order_id')
                ->setParameter('order_id', $order->getId())
                ->setParameter('name', $order->getMaterialData()->getTitle());
        }

        return $qb->getQuery()->execute();
    }

    public function findCreatedRequestDestinyDistributionFor($instance, $type, $initialYear, $finalYear)
    {
        $rsm = new \Doctrine\ORM\Query\ResultSetMapping();
        $rsm->addScalarResult('countryId', 'countryId')
            ->addScalarResult('countryName', 'countryName')
            ->addScalarResult('year', 'year')
            ->addScalarResult('requestsCount', 'requestsCount');

        $sql = 'SELECT c.id countryId, c.name countryName, YEAR(e.created_at) year, COUNT(e.id) requestsCount'
            . ' FROM event e'
            . ' INNER JOIN request r ON r.id = e.request_id'
            . ' INNER JOIN provider p ON p.id = e.provider_id'
            . ' INNER JOIN country c ON c.id = p.country_id'
            . ' WHERE e.deleted_at IS NULL AND';

        if (!is_null($instance)) {
            $sql .= ' e.instance_id = :instance'
                . ' AND';
        }

        $sql .= ' YEAR(e.created_at) >= :initialYear AND YEAR(e.created_at) <= :finalYear'
            . ' AND r.type = :type'
            . ' AND (e.type = :stateTypeA OR e.type = :stateTypeB)'
            . ' GROUP BY countryId, countryName, year'
            . ' ORDER BY requestsCount DESC';

        $query = $this->getEntityManager()->createNativeQuery($sql, $rsm);

        if (!is_null($instance)) {
            $query = $query->setParameter('instance', $instance);
        }

        return $query->setParameter('type', $type)
            ->setParameter('stateTypeA', 'sirequest')
            ->setParameter('stateTypeB', 'mirequest')
            ->setParameter('initialYear', $initialYear)
            ->setParameter('finalYear', $finalYear)
            ->getResult();
    }

    public function findCancelledRequestDestinyDistributionFor($instance, $type, $initialYear, $finalYear)
    {
        $rsm = new \Doctrine\ORM\Query\ResultSetMapping();
        $rsm->addScalarResult('countryId', 'countryId')
            ->addScalarResult('countryName', 'countryName')
//                ->addScalarResult('year', 'year')
            ->addScalarResult('requestsCount', 'requestsCount');

        $sql = 'SELECT c.id countryId, c.name countryName,  COUNT(re.id) requestsCount'
            . ' FROM event e'
            . ' INNER JOIN state s ON s.id = e.state_id'
            . ' INNER JOIN request r ON r.id = e.request_id'
            . ' INNER JOIN event re ON r.id = re.request_id'
            . ' INNER JOIN provider p ON p.id = re.provider_id'
            . ' INNER JOIN country c ON c.id = p.country_id'
            . ' WHERE e.deleted_at IS NULL AND';

        if (!is_null($instance)) {
            $sql .= ' e.instance_id = :instance'
                . ' AND';
        }

        $sql .= ' YEAR(e.created_at) >= :initialYear AND YEAR(e.created_at) <= :finalYear'
            . ' AND r.type = :type'
            . ' AND s.current = :current'
            . ' AND (e.type = :stateTypeA OR e.type = :stateTypeB)'
            . ' AND (re.type = :stateTypeC OR re.type = :stateTypeD)'
            . ' GROUP BY c.id'
            . ' ORDER BY requestsCount DESC';

        $query = $this->getEntityManager()->createNativeQuery($sql, $rsm);

        if (!is_null($instance)) {
            $query = $query->setParameter('instance', $instance);
        }

        $query->setParameter('type', $type)
            ->setParameter('stateTypeA', 'cancel')
            ->setParameter('stateTypeB', 'localcancel')
            ->setParameter('stateTypeC', 'sirequest')
            ->setParameter('stateTypeD', 'mirequest')
            ->setParameter('initialYear', $initialYear)
            ->setParameter('finalYear', $finalYear)
            ->setParameter('current', true);

        return $query->getResult();
    }

    public function findDeliveredRequestDestinyDistributionFor($instance, $type, $initialYear, $finalYear)
    {
        $rsm = new \Doctrine\ORM\Query\ResultSetMapping();
        $rsm->addScalarResult('countryId', 'countryId')
            ->addScalarResult('countryName', 'countryName')
            ->addScalarResult('year', 'year')
            ->addScalarResult('requestsCount', 'requestsCount');

        $sql = 'SELECT c.id countryId, c.name countryName, YEAR(e.created_at) year, COUNT(e.id) requestsCount'
            . ' FROM event e'
            . ' INNER JOIN event re ON re.id = e.request_event_id'
            . ' INNER JOIN request r ON r.id = re.request_id'
            . ' INNER JOIN provider p ON p.id = re.provider_id'
            . ' INNER JOIN country c ON c.id = p.country_id'
            . ' WHERE e.deleted_at IS NULL AND';

        if (!is_null($instance)) {
            $sql .= ' e.instance_id = :instance'
                . ' AND';
        }

        $sql .= ' YEAR(e.created_at) >= :initialYear AND YEAR(e.created_at) <= :finalYear'
            . ' AND r.type = :type'
            . ' AND (e.type = :stateTypeA OR e.type = :stateTypeB)'
            . ' GROUP BY countryId, countryName, year'
            . ' ORDER BY requestsCount DESC';

        $query = $this->getEntityManager()->createNativeQuery($sql, $rsm);

        if (!is_null($instance)) {
            $query = $query->setParameter('instance', $instance);
        }

        return $query->setParameter('type', $type)
            ->setParameter('stateTypeA', 'sireceive')
            ->setParameter('stateTypeB', 'mireceive')
            ->setParameter('initialYear', $initialYear)
            ->setParameter('finalYear', $finalYear)
            ->getResult();
    }

    public function getPreviousJournalSearches(Instance $instance, Journal $journal)
    {
        $qb = $this->generalSearch();

        $qb->where('jt.journal IS NOT NULL')
            ->andWhere('s.instance = :instance')->setParameter('instance', $instance->getId())
            ->andWhere('jt.journal = :journal')->setParameter('journal', $journal->getId())
            ->andWhere('s.result IN (:result)')->setParameter('result', [CatalogManager::CATALOG__FOUND, CatalogManager::CATALOG__PARTIALLY_FOUND]);

        $qb->orderBy('s.result', 'ASC')
            ->addOrderBy('s.createdAt', 'DESC');

        return $qb->getQuery()->getResult();
    }

    public function getPreviousJournalReceivedRequests(Instance $instance, Journal $journal)
    {
        $sir = $this->getPreviousJournalReceivedRequestsFor($instance, $journal, SingleInstanceRequestEvent::class, SingleInstanceReceiveEvent::class);
        $mir = $this->getPreviousJournalReceivedRequestsFor($instance, $journal, MultiInstanceRequestEvent::class, MultiInstanceReceiveEvent::class);

        $results = array_merge($sir, $mir);

        usort($results, function (Event $a, Event $b) {
            if ($a->getCreatedAt() == $b->getCreatedAt()) {
                return 0;
            }
            return ($a->getCreatedAt() > $b->getCreatedAt()) ? -1 : 1;
        });

        return $results;
    }

    private function getPreviousJournalReceivedRequestsFor(Instance $instance, Journal $journal, $requestEventClass, $receiveEventClass)
    {
        $qb = $this->createQueryBuilder('e');

        $qb->select('erec, ereq, r, o, md')
            ->innerJoin($receiveEventClass, 'erec', Join::WITH, 'e = erec')
            ->innerJoin($requestEventClass, 'ereq', Join::WITH, 'erec.requestEvent = ereq')
            ->innerJoin('ereq.request', 'r')
            ->innerJoin('r.order', 'o')
            ->innerJoin('o.materialData', 'md')
            ->innerJoin(JournalType::class, 'jt', Join::WITH, 'md = jt')
            ->innerJoin(Institution::class, 'i', Join::WITH, 'ereq.provider = i');

        $qb->where('jt.journal IS NOT NULL')
            ->andWhere('ereq.instance = :instance')->setParameter('instance', $instance->getId())
            ->andWhere('jt.journal = :journal')->setParameter('journal', $journal->getId());

        $qb->orderBy('erec.createdAt', 'DESC');

         return array_filter($qb->getQuery()->getResult(), function ($var) use ($receiveEventClass) {
            return ($var instanceof $receiveEventClass) ? true : false;
        });
    }
}
