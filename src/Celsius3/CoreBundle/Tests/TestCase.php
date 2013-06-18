<?php

/**
 * Base test case for the bundle test suite
 */

namespace Celsius\Celsius3Bundle\Tests;

require_once dirname(__DIR__) . '/../../../app/AppKernel.php';

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

    protected function runConsole($command, Array $options = array())
    {
        $options["-e"] = "test";
        $options["-q"] = null;
        $options = array_merge($options, array('command' => $command));
        return $this->_application->run(new \Symfony\Component\Console\Input\ArrayInput($options));
    }

}