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
use Celsius3\CoreBundle\Document\Login;

class LoginTest extends TestCase
{

    protected $login;

    public function setUp()
    {
        parent::setUp();

        $this->login = new Login();
    }

    public function testGetCategory()
    {
        $category = 'Sample';

        $this->login->setCategory($category);

        $this->assertEquals($category, $this->login->getCategory());
    }

    public function testGetDate()
    {
        $date = time();

        $this->login->setDate($date);

        $this->assertEquals($date, $this->login->getDate());
    }

    public function testGetMessage()
    {
        $message = 'Sample message';

        $this->login->setMessage($message);

        $this->assertEquals($message, $this->login->getMessage());
    }

    public function testGenerateLogin()
    {
        $category = 'Sample';
        $date = time();
        $message = 'Sample message';

        $this->login->setCategory($category);
        $this->login->setDate($date);
        $this->login->setMessage($message);

        $this->assertNull($this->login->getId());

        $this->documentManager->persist($this->login);
        $this->documentManager->flush();

        $this->assertNotNull($this->login->getId());
    }

    public function tearDown()
    {
        parent::tearDown();

        unset($this->login);
    }

}