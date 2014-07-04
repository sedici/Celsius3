<?php

namespace Celsius3\CoreBundle\Tests\Document;

use Celsius3\CoreBundle\Tests\TestCase;
use Celsius3\CoreBundle\Document\City;
use Celsius3\CoreBundle\Document\Country;
use Celsius3\CoreBundle\Document\Institution;

class CityTest extends TestCase
{
    protected $city;
    protected $country;
    protected $institution;

    public function setUp()
    {
        parent::setUp();

        $this->city = new City();
        $this->country = new Country();
        $this->institution = new Institution();
    }

    public function testGetName()
    {
        $name = 'Sample City';

        $this->city->setName($name);

        $this->assertEquals($name, $this->city->getName());
    }

    public function testGetPostalCode()
    {
        $postalCode = 'A1234LH';

        $this->city->setPostalCode($postalCode);

        $this->assertEquals($postalCode, $this->city->getPostalCode());
    }

    public function testGetCountry()
    {
        $this->city->setCountry($this->country);

        $this->assertEquals($this->country, $this->city->getCountry());
    }

    public function testGetInstitutions()
    {
        $this->city->addInstitutions($this->institution);

        $this->assertContains($this->institution, $this->city->getInstitutions());
    }

    public function testGenerateCity()
    {
        $name = 'Sample City';
        $postalCode = 'A1234LH';

        $this->city->setName($name);
        $this->city->setPostalCode($postalCode);

        $this->assertNull($this->city->getId());

        $this->documentManager->persist($this->city);
        $this->documentManager->flush();

        $this->assertNotNull($this->city->getId());
    }

    public function tearDown()
    {
        parent::tearDown();

        unset($this->city, $this->country, $this->institution);
    }
}
