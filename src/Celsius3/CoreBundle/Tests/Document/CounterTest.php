<?php

namespace Celsius3\CoreBundle\Tests\Document;

use Celsius3\CoreBundle\Tests\TestCase;
use Celsius3\CoreBundle\Document\Counter;

class CounterTest extends TestCase
{

    protected $counter;

    public function setUp()
    {
        parent::setUp();

        $this->counter = new Counter();
    }

    public function testGetName()
    {
        $name = 'Sample name';

        $this->counter->setName($name);

        $this->assertEquals($name, $this->counter->getName());
    }

    public function testGetValue()
    {
        $value = 0;

        $this->counter->setValue($value);

        $this->assertEquals($value, $this->counter->getValue());
    }

    public function testGenerateConfiguration()
    {
        $name = 'Sample name';
        $value = 0;

        $this->counter->setName($name);
        $this->counter->setValue($value);

        $this->assertNull($this->counter->getId());

        $this->documentManager->persist($this->counter);
        $this->documentManager->flush();

        $this->assertNotNull($this->counter->getId());
    }

    public function tearDown()
    {
        parent::tearDown();

        unset($this->counter);
    }

}
