<?php

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
