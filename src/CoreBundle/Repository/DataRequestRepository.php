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

declare(strict_types=1);

namespace Celsius3\CoreBundle\Repository;

use Celsius3\CoreBundle\Entity\DataRequest;
use Celsius3\CoreBundle\Entity\Instance;
use DateInterval;
use DateTime;
use Doctrine\ORM\EntityRepository;

class DataRequestRepository extends EntityRepository
{
    public function findExportedRequests(Instance $instance)
    {
        $qb = $this->createQueryBuilder('dr');

        $qb->select('dr.id')
            ->addSelect('dr.file')
            ->where('dr.instance = :instance')
            ->andWhere('dr.exported = :exported')
            ->andWhere('dr.visible = :visible')
            ->andWhere('dr.file IS NOT NULL')
            ->setParameter('instance', $instance->getId())
            ->setParameter('visible', true)
            ->setParameter('exported', true);

        return $qb->getQuery()->getArrayResult();
    }

    public function findAllDownloadedOrExpired()
    {
        $date = (new DateTime())->sub(new DateInterval('P3D'));

        $qb = $this->createQueryBuilder('dr');

        $qb->select('dr')
            ->where('dr.downloaded = :downloaded')
            ->orWhere('dr.deletedAt < :date')
            ->setParameter('downloaded', true)
            ->setParameter('date', $date);

        return $qb->getQuery()->execute();
    }

    public function save(DataRequest $dataRequest): void
    {
        $this->getEntityManager()->persist($dataRequest);
        $this->getEntityManager()->flush();
    }
}
