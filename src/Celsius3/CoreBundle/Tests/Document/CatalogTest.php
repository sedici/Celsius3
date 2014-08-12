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
use Celsius3\CoreBundle\Document\Catalog;
use Celsius3\CoreBundle\Document\Institution;
use Celsius3\CoreBundle\Document\Instance;

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