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

namespace Celsius3\CoreBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Directory controller
 *
 */
class DirectoryController extends BaseController
{

    /**
     * @Template()
     *
     * @return array
     */
    public function indexAction()
    {
        return array(
            'directory' => $this->getDirectory(),
            'lastNews' => $this->getDoctrine()->getManager()
                    ->getRepository('Celsius3CoreBundle:News')
                    ->findLastNews($this->getDirectory()),
        );
    }

    /**
     * @Template()
     *
     * @return array
     */
    public function instancesAction()
    {
        $instances = $this->getDoctrine()->getManager()
                ->getRepository('Celsius3CoreBundle:Instance')
                ->createQueryBuilder('i')
                ->select('o, c, i')
                ->innerJoin('i.ownerInstitutions', 'o')
                ->innerJoin('o.country', 'c')
                ->where('i.enabled = true')
                ->andWhere('i.invisible = :invisible')
                ->setParameter('invisible', false)
                ->getQuery()
                ->getResult();

        $legacyInstances = $this->getDoctrine()->getManager()
                ->getRepository('Celsius3CoreBundle:LegacyInstance')
                ->createQueryBuilder('li')
                ->select('o, c, li')
                ->innerJoin('li.ownerInstitutions', 'o')
                ->innerJoin('o.country', 'c')
                ->where('li.enabled = true')
                ->andWhere('li INSTANCE OF Celsius3CoreBundle:LegacyInstance')
                ->getQuery()
                ->getResult();

        $cInstances = array();
        foreach ($instances as $instance) {
            if (!array_key_exists($instance->getOwnerInstitutions()->first()->getCountry()->getName(), $cInstances)) {
                $cInstances[$instance->getOwnerInstitutions()->first()->getCountry()->getName()] = array();
            }
            $cInstances[$instance->getOwnerInstitutions()->first()->getCountry()->getName()][] = $instance;
        }

        $lInstances = array();
        foreach ($legacyInstances as $instance) {
            if (!array_key_exists($instance->getOwnerInstitutions()->first()->getCountry()->getName(), $lInstances)) {
                $lInstances[$instance->getOwnerInstitutions()->first()->getCountry()->getName()] = array();
            }
            $lInstances[$instance->getOwnerInstitutions()->first()->getCountry()->getName()][] = $instance;
        }

        return array(
            'directory' => $this->getDirectory(),
            'instances' => $cInstances,
            'legacyInstances' => $lInstances,
        );
    }

    /**
     * @Template()
     */
    public function statisticsAction()
    {
        return array(
            'directory' => $this->getDirectory()
        );
    }

}
