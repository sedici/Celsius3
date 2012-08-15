<?php

namespace Celsius\Celsius3Bundle\Tests\Document;

use Celsius\Celsius3Bundle\Tests\TestCase;
use Celsius\Celsius3Bundle\Document\Notification;
use Celsius\Celsius3Bundle\Document\Order;
use Celsius\Celsius3Bundle\Document\Instance;

class NotificationTest extends TestCase
{
    protected $notification;
    protected $order;
    protected $source;
    protected $target;
    
    public function setUp()
    {
        parent::setUp();

        $this->notification = new Notification();
        $this->order = new Order();
        $this->source = new Instance();
        $this->target = new Instance();
    }
    
    public function testGetType()
    {
        $type = 'error';

        $this->notification->setType($type);

        $this->assertEquals($type, $this->notification->getType());
    }
    
    public function testGetText()
    {
        $text = 'some text';

        $this->notification->setText($text);

        $this->assertEquals($text, $this->notification->getText());
    }
    
    public function testGetCreated()
    {
        $created = date('Y-m-d H:i:s');

        $this->notification->setCreated($created);

        $this->assertEquals($created, $this->notification->getCreated());
    }
    
    public function testGetViewed()
    {
        $viewed = false;

        $this->notification->setViewed($viewed);

        $this->assertEquals($viewed, $this->notification->getViewed());
    }
    
    public function testGetOrder()
    {
        $this->notification->setOrder($this->order);

        $this->assertEquals($this->order, $this->notification->getOrder());
    }
    
    public function testGetSource()
    {
        $this->notification->setSource($this->source);

        $this->assertEquals($this->source, $this->notification->getSource());
    }
    
    public function testGetTarget()
    {
        $this->notification->setTarget($this->target);

        $this->assertEquals($this->target, $this->notification->getTarget());
    }
    
    public function testGenerateNotification()
    {
        $type = 'error';
        $text = 'some text';
        $created = date('Y-m-d H:i:s');
        $viewed = false;

        $this->notification->setType($type);
        $this->notification->setText($text);
        $this->notification->setCreated($created);
        $this->notification->setViewed($viewed);
        
        $this->assertNull($this->notification->getId());

        $this->documentManager->persist($this->notification);
        $this->documentManager->flush();

        $this->assertNotNull($this->notification->getId());
    }
    
    public function tearDown()
    {
        parent::tearDown();

        unset($this->notification);
        unset($this->order);
        unset($this->source);
        unset($this->target);
    }
}