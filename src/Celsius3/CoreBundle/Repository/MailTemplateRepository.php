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

use Celsius3\CoreBundle\Entity\Instance;
use Celsius3\CoreBundle\Entity\MailTemplate;
use Celsius3\CoreBundle\Manager\InstanceManager;

/**
 * MailTemplateRepository.
 */
class MailTemplateRepository extends BaseRepository
{
    public function findGlobalAndForInstance(Instance $instance, Instance $directory, $code = null)
    {
        $custom = $this->createQueryBuilder('c')
                        ->select('c.code')
                        ->where('c.instance = :instance_id')
                        ->andWhere('c.enabled = true')
                        ->setParameter('instance_id', $instance->getId())
                        ->getQuery()->getResult();

        $query = $this->createQueryBuilder('e')
                        ->where('e.instance = :directory_id')
                        ->andWhere('e.code NOT IN (:codes)')
                        ->andWhere('e.enabled = true')
                        ->orWhere('e.instance = :instance_id')
                        ->setParameter('directory_id', $directory->getId())
                        ->setParameter('codes', count($custom) !== 0 ? $custom : array(1)) // El NOT IN no funciona correctamente con un array vacio
                        ->setParameter('instance_id', $instance->getId());

        if (!is_null($code)) {
            $query->andWhere('e.code = :code')
                ->setParameter('code', $code);
        }

        return $query;
    }

    public function findAllEnabled()
    {
        return $this->createQueryBuilder('t')
                        ->select('t')
                        ->where('t.enabled = :enabled')
                        ->setParameter('enabled', true)
                        ->getQuery()->getResult();
    }

    public function templateEdited(MailTemplate $template)
    {
        $qb = $this->createQueryBuilder('mt');

        $qb->select('mt')
            ->innerJoin('mt.instance', 'i')
            ->where('mt.code = :code')
            ->setParameter('code', $template->getCode())
            ->andWhere('mt.instance = :instance')
            ->setParameter('instance', $template->getInstance())
            ->andWhere('i.url != :directory')
            ->setParameter('directory', 'directory');

        return $qb->getQuery()->getResult();
    }
}
