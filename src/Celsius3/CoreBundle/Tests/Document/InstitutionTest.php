<?php

namespace Celsius\Celsius3Bundle\Tests\Document;

use Celsius\Celsius3Bundle\Tests\TestCase;
use Celsius\Celsius3Bundle\Document\Institution;
use Celsius\Celsius3Bundle\Document\BaseUser;
use Celsius\Celsius3Bundle\Document\City;
use Celsius\Celsius3Bundle\Document\Instance;
use Celsius\Celsius3Bundle\Document\Catalog;

class InstitutionTest extends TestCase
{
    protected $institution;
    protected $user;
    protected $subinstitution;
    protected $parent;
    protected $city;
    protected $instance;
    protected $catalog;
    
    
    public function setUp()
    {
        parent::setUp();

        $this->institution = new Institution();
        $this->user = new BaseUser();
        $this->subinstitution = new Institution();
        $this->parent = new Institution();
        $this->city = new City();
        $this->instance = new Instance();
        $this->catalog = new Catalog();
    }
    
    public function testGetName()
    {
        $name = 'Institution Name';

        $this->institution->setName($name);

        $this->assertEquals($name, $this->institution->getName());
    }
    
    public function testGetAbbreviation()
    {
        $abbreviation = 'IN';

        $this->institution->setAbbreviation($abbreviation);

        $this->assertEquals($abbreviation, $this->institution->getAbbreviation());
    }
    
    public function testGetWebsite()
    {
        $website = 'http://institution.website.com';

        $this->institution->setWebsite($website);

        $this->assertEquals($website, $this->institution->getWebsite());
    }
    
    public function testGetAddress()
    {
        $address = 'Calle x nro 123';

        $this->institution->setAddress($address);

        $this->assertEquals($address, $this->institution->getAddress());
    }
    
    public function testGetUsers()
    {
        $this->institution->addUsers($this->user);

        $this->assertContains($this->user, $this->institution->getUsers());
    }
    
    public function testGetInstitutions()
    {
        $this->institution->addInstitutions($this->subinstitution);

        $this->assertContains($this->subinstitution, $this->institution->getInstitutions());
    }
    
    public function testGetParent()
    {
        $this->institution->setParent($this->parent);

        $this->assertEquals($this->parent, $this->institution->getParent());
    }
    
    public function testGetCity()
    {
        $this->institution->setCity($this->city);

        $this->assertEquals($this->city, $this->institution->getCity());
    }
    
    public function testGetCatalog()
    {
        $this->institution->setCatalog($this->catalog);

        $this->assertEquals($this->catalog, $this->institution->getCatalog());
    }
    
    public function testGetInstance()
    {
        $this->institution->setInstance($this->instance);

        $this->assertEquals($this->instance, $this->institution->getInstance());
    }
    
    public function testGenerateInstance()
    {
        $name = 'Institution Name';
        $abbreviation = 'IN';
        $website = 'http://institution.website.com';
        $address = 'Calle x nro 123';
        
        $this->institution->setName($name);
        $this->institution->setAbbreviation($abbreviation);
        $this->institution->setWebsite($website);
        $this->institution->setAddress($address);
        
        $this->assertNull($this->institution->getId());

        $this->documentManager->persist($this->institution);
        $this->documentManager->flush();

        $this->assertNotNull($this->institution->getId());
    }
    
    public function tearDown()
    {
        parent::tearDown();

        unset($this->institution, $this->instance, $this->user, $this->subinstitution,
                $this->parent, $this->city, $this->catalog);
    }
}