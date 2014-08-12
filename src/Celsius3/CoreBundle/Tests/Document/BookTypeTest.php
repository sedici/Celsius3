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

use Celsius3\CoreBundle\Document\BookType;

class BookTypeTest extends MaterialTypeTest
{

    public function setUp()
    {
        parent::setUp();

        $this->material = new BookType();
    }

    public function testGetEditor()
    {
        $editor = 'Sample editor';

        $this->material->setEditor($editor);

        $this->assertEquals($editor, $this->material->getEditor());
    }

    public function testGetChapter()
    {
        $chapter = 'VII';

        $this->material->setChapter($chapter);

        $this->assertEquals($chapter, $this->material->getChapter());
    }

    public function testGetISBN()
    {
        $ISBN = '978-3-16-148410-0';

        $this->material->setISBN($ISBN);

        $this->assertEquals($ISBN, $this->material->getISBN());
    }

    public function testGetWithIndex()
    {
        $withIndex = true;

        $this->material->setWithIndex($withIndex);

        $this->assertEquals($withIndex, $this->material->getWithIndex());
    }

}