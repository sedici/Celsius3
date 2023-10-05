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

namespace Celsius3\Controller;

use Celsius3\Entity\Instance;
use Celsius3\Helper\ConfigurationHelper;
use Celsius3\Helper\InstanceHelper;
use Celsius3\Manager\FilterManager;
use Celsius3\Manager\InstanceManager;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;

abstract class BaseInstanceDependentController extends BaseController
{

    /**
     * @var ConfigurationHelper
     */
    private $configurationHelper;

    /**
     * @var ConfigurationHelper
     */
    private $instanceHelper;

    /**
     * @var Paginator
     */
    private $paginator;


    public function __construct(InstanceHelper $instanceHelper,
                                PaginatorInterface $paginator,
    ConfigurationHelper $configurationHelper

    )
    {
        $this->configurationHelper = $configurationHelper;
        $this->paginator=$paginator;
        $this->instanceHelper=$instanceHelper;
    }

    public function setConfigurationHelper(ConfigurationHelper $configurationHelper){

        return $this->configurationHelper=$configurationHelper;
    }

    public function setIntanceHelper(InstanceHelper $intanceHelper){

        return $this->intanceHelper=$intanceHelper;
    }


    public function getConfigurationHelper(){
        return $this->configurationHelper;
    }

    public function getInstanceHelper(){
        return $this->instanceHelper;
    }


    protected function listQuery($name)
    {
        return parent::listQuery($name)
            ->andWhere('e.instance = :instance_id')
            ->setParameter('instance_id', $this->getInstance()->getId());
    }

    protected function getInstance(): Instance
    {
        return $this->getInstanceHelper()->getSessionInstance();
    }

    protected function findQuery($name, $id)
    {
        return $this->getDoctrine()->getManager()
            ->getRepository($this->getBundle().':'.$name)
            ->findOneForInstance($this->getInstance(), $id);
    }

    protected function getResultsPerPage()
    {
        return $this->getConfigurationHelper()->getCastedValue($this->getInstance()->get('results_per_page'));
    }

    protected function filter($name, $filter_form, $query)
    {
        return $this->getDoctrine()->getManager()
            ->getRepository(FilterManager::class)
            ->filter($query, $filter_form, 'Celsius3\\Entity\\'.$name, $this->getInstance());
    }
}
