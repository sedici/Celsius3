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
use Celsius3\CoreBundle\Document\MaterialType;

class MaterialTypeTest extends TestCase
{

    protected $material;

    public function setUp()
    {
        parent::setUp();

        $this->material = $this->getMockForAbstractClass('Celsius\\Celsius3Bundle\\Document\\MaterialType');
    }

    public function testToString()
    {
        $title = 'Sample title';

        $this->material->setTitle($title);

        $this->assertEquals($title, $this->material->__toString());
    }

    public function testGetId()
    {
        $this->assertNull($this->material->getId());
    }

    public function testGetTitle()
    {
        $title = 'Sample title';

        $this->material->setTitle($title);

        $this->assertEquals($title, $this->material->getTitle());
    }

    public function testGetAuthors()
    {
        $authors = 'Sample authors';

        $this->material->setAuthors($authors);

        $this->assertEquals($authors, $this->material->getAuthors());
    }

    public function testGetYear()
    {
        $year = 1917;

        $this->material->setYear($year);

        $this->assertEquals($year, $this->material->getYear());
    }

    public function testGetStartPage()
    {
        $startPage = 121;

        $this->material->setStartPage($startPage);

        $this->assertEquals($startPage, $this->material->getStartPage());
    }

    public function testGetEndPage()
    {
        $endPage = 214;

        $this->material->setEndPage($endPage);

        $this->assertEquals($endPage, $this->material->getEndPage());
    }

    public function tearDown()
    {
        parent::tearDown();

        unset($this->material);
    }

}