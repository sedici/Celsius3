<?php
/*
 * Celsius3 - Order management
 * Copyright (C) 2014 PrEBi <info@prebi.unlp.edu.ar>
 *
 * This file is part of Celsius3.
 *
 * Celsius3 is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Celsius3 is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Celsius3.  If not, see <http://www.gnu.org/licenses/>.
 */

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