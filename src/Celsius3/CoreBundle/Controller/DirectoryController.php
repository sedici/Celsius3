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
                ->innerJoin('i.ownerInstitutions','owner')
                ->where('i.enabled = true')
                ->orderBy('owner.country')
                ->getQuery()
                ->getResult();

        $legacyInstances = $this->getDoctrine()->getManager()
                ->getRepository('Celsius3CoreBundle:LegacyInstance')
                ->createQueryBuilder('li')
                ->innerJoin('li.ownerInstitutions','owner')
                ->where('li.enabled = true')
                ->andWhere('li INSTANCE OF Celsius3CoreBundle:LegacyInstance')
                ->orderBy('owner.country')
                ->getQuery()
                ->getResult();

        return array(
            'directory' => $this->getDirectory(),
            'instances' => $instances,
            'legacyInstances' => $legacyInstances,
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
