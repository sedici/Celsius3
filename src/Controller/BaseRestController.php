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

use Celsius3\Helper\ConfigurationHelper;
use Celsius3\Helper\InstanceHelper;
use Celsius3\Manager\InstanceManager;
use FOS\RestBundle\Controller\AbstractFOSRestController;

class BaseRestController extends AbstractFOSRestController
{

    /**
     * @var InstanceManager
     */
    private $instanceManager;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var ConfigurationHelper
     */
    private $configurationHelper;





    public function __construct(InstanceManager $instanceManager,
                                InstanceHelper $instanceHelper,
                                ConfigurationHelper  $configurationHelper

    )
    {
        $this->instanceManager = $instanceManager;
        $this->configurationHelper = $configurationHelper;

    }
    public function getConfigurationHelper(){
        return $this->configurationHelper;
    }
    public function setConfigurationHelper(ConfigurationHelper $configurationHelper){
        return $this->configurationHelper=$configurationHelper;
    }

    public function setTranslator(TranslatorInterface $translator){
        $this->translator=$translator;
    }

    protected function getDirectory()
    {
        return $this->instanceManager->getDirectory();
    }



}
