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
use Celsius3\CoreBundle\Document\Contact;
use Celsius3\CoreBundle\Document\BaseUser;
use Celsius3\CoreBundle\Document\ContactType;
use Celsius3\CoreBundle\Document\Instance;

class ContactTest extends TestCase
{

    protected $contact;
    protected $type;
    protected $user;
    protected $instance;

    public function setUp()
    {
        parent::setUp();

        $this->contact = new Contact();
        $this->type = new ContactType();
        $this->user = new BaseUser();
        $this->instance = new Instance();
    }

    public function testGetName()
    {
        $name = 'John';

        $this->contact->setName($name);

        $this->assertEquals($name, $this->contact->getName());
    }

    public function testGetSurname()
    {
        $surname = 'Doe';

        $this->contact->setSurname($surname);

        $this->assertEquals($surname, $this->contact->getSurname());
    }

    public function testGetEmail()
    {
        $email = 'johndoe@test.com.ar';

        $this->contact->setEmail($email);

        $this->assertEquals($email, $this->contact->getEmail());
    }

    public function testGetAddress()
    {
        $address = 'Calle x nro 123';

        $this->contact->setAddress($address);

        $this->assertEquals($address, $this->contact->getAddress());
    }

    public function testGetUser()
    {
        $this->contact->setUser($this->user);

        $this->assertEquals($this->user, $this->contact->getUser());
    }

    public function testGetType()
    {
        $this->contact->setType($this->type);

        $this->assertEquals($this->type, $this->contact->getType());
    }

    public function testGetInstance()
    {
        $this->contact->setInstance($this->instance);

        $this->assertEquals($this->instance, $this->contact->getInstance());
    }

    public function testGenerateState()
    {
        $name = 'John';
        $surname = 'Doe';
        $email = 'johndoe@test.com.ar';
        $address = 'Calle x nro 123';

        $this->contact->setName($name);
        $this->contact->setSurname($surname);
        $this->contact->setEmail($email);
        $this->contact->setAddress($address);

        $this->assertNull($this->contact->getId());

        $this->documentManager->persist($this->contact);
        $this->documentManager->flush();

        $this->assertNotNull($this->contact->getId());
    }

    public function tearDown()
    {
        parent::tearDown();

        unset($this->contact, $this->type, $this->user, $this->instance);
    }

}