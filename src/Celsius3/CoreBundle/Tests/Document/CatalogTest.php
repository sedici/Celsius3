<?php

namespace Celsius\Celsius3Bundle\Tests\Document;

use Celsius\Celsius3Bundle\Tests\TestCase;
use Celsius\Celsius3Bundle\Document\Catalog;
use Celsius\Celsius3Bundle\Document\Institution;
use Celsius\Celsius3Bundle\Document\Instance;

class CatalogTest extends TestCase
{
    protected $catalog;
    protected $institution;
    protected $instance;
    
    public function setUp()
    {
        parent::setUp();

        $this->catalog = new Catalog();
        $this->institution = new Institution();
        $this->instance = new Instance();
    }
    
    public function testGetName()
    {
        $name = 'Catalog Name';

        $this->catalog->setName($name);

        $this->assertEquals($name, $this->catalog->getName());
    }
    
    public function testGetUrl()
    {
        $url = 'http://catalog.test.com';

        $this->catalog->setUrl($url);

        $this->assertEquals($url, $this->catalog->getUrl());
    }
    
    public function testGetComments()
    {
        $comments = 'Some comments';

        $this->catalog->setComments($comments);

        $this->assertEquals($comments, $this->catalog->getComments());
    }
    
    public function testGetInstitution()
    {
        $this->catalog->setInstitution($this->institution);

        $this->assertEquals($this->institution, $this->catalog->getInstitution());
    }
    
    public function testGetInstance()
    {
        $this->catalog->setInstance($this->instance);

        $this->assertEquals($this->instance, $this->catalog->getInstance());
    }
    
    public function testGenerateCatalog()
    {
        $name = 'Catalog Name';
        $url = 'http://catalog.test.com';
        $comments = 'Some comments';

        $this->catalog->setName($name);
        $this->catalog->setUrl($url);
        $this->catalog->setComments($comments);
        
        $this->assertNull($this->catalog->getId());

        $this->documentManager->persist($this->catalog);
        $this->documentManager->flush();

        $this->assertNotNull($this->catalog->getId());
    }
    
    public function tearDown()
    {
        parent::tearDown();

        unset($this->catalog);
        unset($this->institution);
        unset($this->instance);
    }
    
}