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
use Celsius3\CoreBundle\Document\MailTemplate;
use Celsius3\CoreBundle\Document\Instance;

class MailTemplateTest extends TestCase
{

    protected $mailTemplate;
    protected $instance;

    public function setUp()
    {
        parent::setUp();

        $this->mailTemplate = new MailTemplate();
        $this->instance = new Instance();
    }

    public function testGetTitle()
    {
        $title = 'Sample title';

        $this->mailTemplate->setTitle($title);

        $this->assertEquals($title, $this->mailTemplate->getTitle());
    }

    public function testGetText()
    {
        $text = 'Sample text';

        $this->mailTemplate->setText($text);

        $this->assertEquals($text, $this->mailTemplate->getText());
    }

    public function testGetInstance()
    {
        $this->mailTemplate->setInstance($this->instance);

        $this->assertEquals($this->instance, $this->mailTemplate->getInstance());
    }

    public function testGenerateMailTemplate()
    {
        $title = 'Sample title';
        $text = 'Sample text';

        $this->mailTemplate->setTitle($title);
        $this->mailTemplate->setText($text);

        $this->assertNull($this->mailTemplate->getId());

        $this->documentManager->persist($this->mailTemplate);
        $this->documentManager->flush();

        $this->assertNotNull($this->mailTemplate->getId());
    }

    public function tearDown()
    {
        parent::tearDown();

        unset($this->mailTemplate, $this->instance);
    }

}