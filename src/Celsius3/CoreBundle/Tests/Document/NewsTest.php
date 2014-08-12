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
use Celsius3\CoreBundle\Document\News;
use Celsius3\CoreBundle\Document\Instance;

class NewsTest extends TestCase
{

    protected $news;
    protected $instance;

    public function setUp()
    {
        parent::setUp();

        $this->news = new News();
        $this->instance = new Instance();
    }

    public function testGetTitle()
    {
        $title = 'Some Title';

        $this->news->setTitle($title);

        $this->assertEquals($title, $this->news->getTitle());
    }

    public function testGetText()
    {
        $text = 'Some Text';

        $this->news->setText($text);

        $this->assertEquals($text, $this->news->getText());
    }

    public function testGetDate()
    {
        $date = date('Y-m-d H:i:s');

        $this->news->setDate($date);

        $this->assertEquals($date, $this->news->getDate());
    }

    public function testGetInstance()
    {
        $this->news->setInstance($this->instance);

        $this->assertEquals($this->instance, $this->news->getInstance());
    }

    public function testGenerateNews()
    {
        $title = 'Some Title';
        $text = 'Some Text';
        $date = date('Y-m-d H:i:s');

        $this->news->setTitle($title);
        $this->news->setText($text);
        $this->news->setDate($date);

        $this->assertNull($this->news->getId());

        $this->documentManager->persist($this->news);
        $this->documentManager->flush();

        $this->assertNotNull($this->news->getId());
    }

    public function tearDown()
    {
        parent::tearDown();

        unset($this->news);
        unset($this->instance);
    }

}