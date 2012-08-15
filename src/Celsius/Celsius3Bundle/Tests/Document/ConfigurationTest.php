<?php

namespace Celsius\Celsius3Bundle\Tests\Document;

use Celsius\Celsius3Bundle\Tests\TestCase;
use Celsius\Celsius3Bundle\Document\Configuration;
use Celsius\Celsius3Bundle\Document\Instance;

class ConfigurationTest extends TestCase
{
    protected $configuration;
    protected $instance;
    
    public function setUp()
    {
        parent::setUp();

        $this->configuration = new Configuration();
        $this->instance = new Instance();
    }
    
    public function testGetKey()
    {
        $key = 'testkey';

        $this->configuration->setKey($key);

        $this->assertEquals($key, $this->configuration->getKey());
    }

    public function testGetValue()
    {
        $value = 'testvalue';

        $this->configuration->setValue($value);

        $this->assertEquals($value, $this->configuration->getValue());
    }
    
    public function testGetInstance()
    {
        $this->configuration->setInstance($this->instance);

        $this->assertEquals($this->instance, $this->configuration->getInstance());
    }
    
    public function testGenerateConfiguration()
    {
        $key = 'testkey';
        $value = 'testvalue';

        $this->configuration->setKey($key);
        $this->configuration->setValue($value);

        $this->documentManager->persist($this->configuration);
        $this->documentManager->flush();

        $this->assertNotNull($this->configuration->getId());
    }
    
    public function tearDown()
    {
        parent::tearDown();

        unset($this->configuration);
        unset($this->instance);
    }
}