<?php

namespace Celsius3\CoreBundle\Tests\Document;

use Celsius3\CoreBundle\Tests\TestCase;
use Celsius3\CoreBundle\Document\Country;
use Celsius3\CoreBundle\Document\City;

class CountryTest extends TestCase
{
    protected $country;
    protected $city;

    public function setUp()
    {
        parent::setUp();

        $this->country = new Country();
        $this->city = new City();
    }

    public function testGetName()
    {
        $name = 'Argentina';

        $this->country->setName($name);

        $this->assertEquals($name, $this->country->getName());
    }

    public function testGetContacts()
    {
        $this->country->addCities($this->city);

        $this->assertContains($this->city, $this->country->getCities());
    }

    public function testGenerateContactType()
    {
        $name = 'Argentina';

        $this->country->setName($name);

        $this->assertNull($this->country->getId());

        $this->documentManager->persist($this->country);
        $this->documentManager->flush();

        $this->assertNotNull($this->country->getId());
    }

    public function tearDown()
    {
        parent::tearDown();

        unset($this->country);
        unset($this->city);
    }
}
