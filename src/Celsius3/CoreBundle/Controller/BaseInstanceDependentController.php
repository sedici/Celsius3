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

abstract class BaseInstanceDependentController extends BaseController
{
    protected function listQuery($name)
    {
        return parent::listQuery($name)
                        ->andWhere('e.instance = :instance_id')
                        ->setParameter('instance_id', $this->getInstance()->getId());
    }

    protected function findQuery($name, $id)
    {
        return $this->getDoctrine()->getManager()
                    ->getRepository($this->getBundle().':'.$name)
                    ->findOneForInstance($this->getInstance(), $id);
    }

    protected function getResultsPerPage()
    {
        return $this->get('celsius3_core.configuration_helper')
                        ->getCastedValue($this->getInstance()->get('results_per_page'));
    }

    protected function filter($name, $filter_form, $query)
    {
        return $this->get('celsius3_core.filter_manager')
                        ->filter($query, $filter_form, 'Celsius3\\CoreBundle\\Entity\\'.$name, $this->getInstance());
    }

    /**
     * Returns the instance related to the users instance.
     *
     * @return Instance
     */
    protected function getInstance()
    {
        return $this->get('celsius3_core.instance_helper')->getSessionInstance();
    }
}
