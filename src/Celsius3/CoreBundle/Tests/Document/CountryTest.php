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