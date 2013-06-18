<?php

namespace Celsius\Celsius3Bundle\Tests\Document;

use Celsius\Celsius3Bundle\Tests\TestCase;
use Celsius\Celsius3Bundle\Document\News;
use Celsius\Celsius3Bundle\Document\Instance;

class NewsTest extends TestCase
{

    protected $news;
    protected $instance;

    public function setUp()
    {
        parent::setUp();

        $this->news = new News();
        $this->instance = new Instance();
    }
    
    public function testGetTitle()
    {
        $title = 'Some Title';

        $this->news->setTitle($title);

        $this->assertEquals($title, $this->news->getTitle());
    }
    
    public function testGetText()
    {
        $text = 'Some Text';

        $this->news->setText($text);

        $this->assertEquals($text, $this->news->getText());
    }
    
    public function testGetDate()
    {
        $date = date('Y-m-d H:i:s');

        $this->news->setDate($date);

        $this->assertEquals($date, $this->news->getDate());
    }
    
    public function testGetInstance()
    {
        $this->news->setInstance($this->instance);

        $this->assertEquals($this->instance, $this->news->getInstance());
    }
    
    public function testGenerateNews()
    {
        $title = 'Some Title';
        $text = 'Some Text';
        $date = date('Y-m-d H:i:s');

        $this->news->setTitle($title);
        $this->news->setText($text);
        $this->news->setDate($date);
        
        $this->assertNull($this->news->getId());

        $this->documentManager->persist($this->news);
        $this->documentManager->flush();

        $this->assertNotNull($this->news->getId());
    }
    
    public function tearDown()
    {
        parent::tearDown();

        unset($this->news);
        unset($this->instance);
    }

}