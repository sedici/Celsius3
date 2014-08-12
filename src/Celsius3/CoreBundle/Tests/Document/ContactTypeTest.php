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
use Celsius3\CoreBundle\Document\ContactType;
use Celsius3\CoreBundle\Document\Contact;

class ContactTypeTest extends TestCase
{

    protected $contactType;
    protected $contact;

    public function setUp()
    {
        parent::setUp();

        $this->contactType = new ContactType();
        $this->contact = new Contact();
    }

    public function testGetName()
    {
        $name = 'Librarian';

        $this->contactType->setName($name);

        $this->assertEquals($name, $this->contactType->getName());
    }

    public function testGetContacts()
    {
        $this->contactType->addContacts($this->contact);

        $this->assertContains($this->contact, $this->contactType->getContacts());
    }

    public function testGenerateContactType()
    {
        $name = 'Librarian';

        $this->contactType->setName($name);

        $this->assertNull($this->contactType->getId());

        $this->documentManager->persist($this->contactType);
        $this->documentManager->flush();

        $this->assertNotNull($this->contactType->getId());
    }

    public function tearDown()
    {
        parent::tearDown();

        unset($this->contactType);
        unset($this->contact);
    }

}