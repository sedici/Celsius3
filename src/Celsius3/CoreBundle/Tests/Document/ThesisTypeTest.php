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

use Celsius3\CoreBundle\Document\ThesisType;

class ThesisTypeTest extends MaterialTypeTest
{

    public function setUp()
    {
        parent::setUp();

        $this->material = new ThesisType();
    }

    public function testGetDirector()
    {
        $director = 'Sample director';

        $this->material->setDirector($director);

        $this->assertEquals($director, $this->material->getDirector());
    }

    public function testGetDegree()
    {
        $degree = 'Grade';

        $this->material->setDegree($degree);

        $this->assertEquals($degree, $this->material->getDegree());
    }

}