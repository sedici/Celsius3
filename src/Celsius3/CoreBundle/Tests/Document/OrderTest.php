<?php

namespace Celsius3\CoreBundle\Tests\Document;

use Celsius3\CoreBundle\Tests\TestCase;
use Celsius3\CoreBundle\Document\Order;
use Celsius3\CoreBundle\Document\JournalType;
use Celsius3\CoreBundle\Document\BaseUser;
use Celsius3\CoreBundle\Document\Admin;
use Celsius3\CoreBundle\Document\Instance;
use Celsius3\CoreBundle\Document\File;
use Celsius3\CoreBundle\Document\Creation;
use Celsius3\CoreBundle\Document\State;

class OrderTest extends TestCase
{

    protected $order;
    protected $materialData;
    protected $owner;
    protected $operator;
    protected $instance;
    protected $file;
    protected $event;
    protected $state;

    public function setUp()
    {
        parent::setUp();

        $this->order = new Order();
        $this->materialData = new JournalType();
        $this->owner = new BaseUser();
        $this->operator = new Admin();
        $this->instance = new Instance();
        $this->file = new File();
        $this->event = new Creation();
        $this->state = new State();
    }

    public function testToString()
    {
        $code = 1;

        $this->order->setCode($code);

        $this->assertEquals(strval($code), $this->order->__toString());
    }

    public function testGetCreated()
    {
        $created = date('Y-m-d H:i:s');

        $this->order->setCreated($created);

        $this->assertEquals($created, $this->order->getCreated());
    }

    public function testGetSearched()
    {
        $searched = date('Y-m-d H:i:s');

        $this->order->setSearched($searched);

        $this->assertEquals($searched, $this->order->getSearched());
    }

    public function testGetRequested()
    {
        $requested = date('Y-m-d H:i:s');

        $this->order->setRequested($requested);

        $this->assertEquals($requested, $this->order->getRequested());
    }

    public function testGetReceived()
    {
        $received = date('Y-m-d H:i:s');

        $this->order->setReceived($received);

        $this->assertEquals($received, $this->order->getReceived());
    }

    public function testGetDelivered()
    {
        $delivered = date('Y-m-d H:i:s');

        $this->order->setDelivered($delivered);

        $this->assertEquals($delivered, $this->order->getDelivered());
    }

    public function testGetComments()
    {
        $comments = 'Some comments';

        $this->order->setComments($comments);

        $this->assertEquals($comments, $this->order->getComments());
    }

    public function testGetCode()
    {
        $code = 1;

        $this->order->setCode($code);

        $this->assertEquals($code, $this->order->getCode());
    }

    public function testGetType()
    {
        $type = 1;

        $this->order->setType($type);

        $this->assertEquals($type, $this->order->getType());
    }

    public function testGetMaterialData()
    {
        $this->order->setMaterialData($this->materialData);

        $this->assertEquals($this->materialData, $this->order->getMaterialData());
    }

    public function testGetOwner()
    {
        $this->order->setOwner($this->owner);

        $this->assertEquals($this->owner, $this->order->getOwner());
    }

    public function testGetOperator()
    {
        $this->order->setOperator($this->operator);

        $this->assertEquals($this->operator, $this->order->getOperator());
    }

    public function testGetInstance()
    {
        $this->order->setInstance($this->instance);

        $this->assertEquals($this->instance, $this->order->getInstance());
    }

    public function testGetFiles()
    {
        $this->order->addFiles($this->file);

        $this->assertContains($this->file, $this->order->getFiles());
    }

    public function testGetEvents()
    {
        $this->order->addEvents($this->event);

        $this->assertContains($this->event, $this->order->getEvents());
    }

    public function testGetStates()
    {
        $this->order->addStates($this->state);

        $this->assertContains($this->state, $this->order->getStates());
    }

    public function testGenerateOrder()
    {
        $type = 1;
        $created = date('Y-m-d H:i:s');
        $searched = date('Y-m-d H:i:s');
        $requested = date('Y-m-d H:i:s');
        $received = date('Y-m-d H:i:s');
        $delivered = date('Y-m-d H:i:s');
        $comments = 'Some comments';

        $this->order->setType($type);
        $this->order->setCreated($created);
        $this->order->setSearched($searched);
        $this->order->setRequested($requested);
        $this->order->setReceived($received);
        $this->order->setDelivered($delivered);
        $this->order->setComments($comments);

        $this->instance->setName('Instance Name');
        $this->instance->setAbbreviation('IN');
        $this->instance->setUrl('http://instance.url.com');
        $this->instance->setWebsite('http://instance.website.com');
        $this->instance->setEmail('instance@email.com');

        $this->documentManager->persist($this->instance);
        $this->documentManager->flush();

        $this->order->setInstance($this->instance);

        $this->assertNull($this->order->getId());

        $this->documentManager->persist($this->order);
        $this->documentManager->flush();

        $this->assertNotNull($this->order->getId());
    }

    public function tearDown()
    {
        parent::tearDown();

        unset($this->order, $this->materialData, $this->owner, $this->operator, $this->instance, $this->file, $this->event, $this->state);
    }

}
