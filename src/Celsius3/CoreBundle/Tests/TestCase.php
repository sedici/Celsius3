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

/**
 * Base test case for the bundle test suite
 */

namespace Celsius3\CoreBundle\Tests;

require_once dirname(__DIR__) . '/../../../app/AppKernel.php';
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

abstract class TestCase extends \PHPUnit_Framework_TestCase
{

    /**
     * @var Symfony\Component\Console\Application
     */
    protected $_application;

    /**
     * @var Symfony\Component\HttpKernel\AppKernel
     */
    protected $kernel;

    /**
     * @var Doctrine\ODM\MongoDB\DocumentManager
     */
    protected $documentManager;

    /**
     * @var Symfony\Component\DependencyInjection\Container
     */
    protected $container;

    public function setUp()
    {
        // Boot the AppKernel in the test environment and with the debug.
        $this->kernel = new \AppKernel('test', true);
        $this->kernel->boot();

        $this->_application = new \Symfony\Bundle\FrameworkBundle\Console\Application($this->kernel);
        $this->_application->setAutoExit(false);

        // Store the container and the entity manager in test case properties
        $this->container = $this->kernel->getContainer();
        $this->documentManager = $this->container->get('doctrine.odm.mongodb.document_manager');

        $this->runConsole('doctrine:mongodb:schema:drop', array('--force' => true));
        //$this->runConsole('doctrine:mongodb:schema:create');
        //$this->runConsole('doctrine:mongodb:fixtures:load', array('--append'));

        parent::setUp();
    }

    public function tearDown()
    {
        // Shutdown the kernel.
        $this->kernel->shutdown();

        //$this->runConsole('doctrine:mongodb:schema:drop', array('--force' => true));

        parent::tearDown();
    }

    protected function runConsole($command, array $options = array())
    {
        $options["-e"] = "test";
        $options["-q"] = null;
        $options = array_merge($options, array('command' => $command));

        return $this->_application->run(new \Symfony\Component\Console\Input\ArrayInput($options));
    }

}