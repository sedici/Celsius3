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

namespace Celsius3\Controller;

use Celsius3\Entity\Instance;
use Celsius3\Helper\ConfigurationHelper;
use Celsius3\Helper\InstanceHelper;

class BaseInstanceDependentRestController extends BaseRestController
{



    /**
     * @var ConfigurationHelper
     */
    private $configurationHelper;

    /**
     * @var ConfigurationHelper
     */
    private $instanceHelper;



    public function __construct(InstanceHelper $instanceHelper,
                                ConfigurationHelper $configurationHelper

    )
    {
        $this->configurationHelper = $configurationHelper;
        $this->instanceHelper=$instanceHelper;
    }

    public function setConfigurationHelper(ConfigurationHelper $configurationHelper){

        return $this->configurationHelper=$configurationHelper;
    }

    public function setIntanceHelper(InstanceHelper $intanceHelper){
        return $this->instanceHelper=$intanceHelper;
    }


    public function getConfigurationHelper(){
        return $this->configurationHelper;
    }

    public function getInstanceHelper(){
        return $this->instanceHelper;
    }





    protected function getInstance(): Instance
    {
        return $this->getInstanceHelper()->getSessionInstance();
    }

    protected function getResultsPerPage()
    {
        return $this-getConfigurationHelper()
                        ->getCastedValue($this->getInstance()->get('results_per_page'));
    }
}
