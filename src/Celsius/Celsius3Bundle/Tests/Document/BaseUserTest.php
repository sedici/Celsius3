<?php

namespace Celsius\Celsius3Bundle\Tests\Document;

use Celsius\Celsius3Bundle\Tests\TestCase;
use Celsius\Celsius3Bundle\Document\BaseUser;
use Celsius\Celsius3Bundle\Document\Order;
use Celsius\Celsius3Bundle\Document\Instance;
use Celsius\Celsius3Bundle\Document\Librarian;
use Celsius\Celsius3Bundle\Document\Institution;
use Celsius\Celsius3Bundle\Document\Message;

class BaseUserTest extends TestCase
{

    protected $user;
    protected $order;
    protected $operatedOrder;
    protected $createdOrder;
    protected $instance;
    protected $librarian;
    protected $institution;
    protected $createdMessage;
    protected $receivedMessage;

    public function setUp()
    {
        parent::setUp();

        $this->user = new BaseUser();
        $this->order = new Order();
        $this->operatedOrder = new Order();
        $this->createdOrder = new Order();
        $this->instance = new Instance();
        $this->librarian = new Librarian();
        $this->institution = new Institution();
        $this->createdMessage = new Message();
        $this->receivedMessage = new Message();
    }
    
    public function testToString()
    {
        $name = 'John';
        $surname = 'Doe';

        $this->user->setName($name);
        $this->user->setSurname($surname);

        $this->assertEquals($surname . ', ' . $name, $this->user->__toString());
    }

    public function testGetName()
    {
        $name = 'John';

        $this->user->setName($name);

        $this->assertEquals($name, $this->user->getName());
    }

    public function testGetSurname()
    {
        $surname = 'Doe';

        $this->user->setSurname($surname);

        $this->assertEquals($surname, $this->user->getSurname());
    }

    public function testGetBirthdate()
    {
        $birthdate = date('Y-m-d');

        $this->user->setBirthdate($birthdate);

        $this->assertEquals($birthdate, $this->user->getBirthdate());
    }

    public function testGetAddress()
    {
        $address = 'Calle x nro 123';

        $this->user->setAddress($address);

        $this->assertEquals($address, $this->user->getAddress());
    }

    public function testGetOrders()
    {
        $this->user->addOrders($this->order);

        $this->assertContains($this->order, $this->user->getOrders());
    }

    public function testGetOperatedOrders()
    {
        $this->user->addOperatedOrders($this->operatedOrder);

        $this->assertContains($this->operatedOrder, $this->user->getOperatedOrders());
    }

    public function testGetCreatedOrders()
    {
        $this->user->addCreatedOrders($this->createdOrder);

        $this->assertContains($this->createdOrder, $this->user->getCreatedOrders());
    }

    public function testGetInstance()
    {
        $this->user->setInstance($this->instance);

        $this->assertEquals($this->instance, $this->user->getInstance());
    }

    public function testGetLibrarian()
    {
        $this->user->setLibrarian($this->librarian);

        $this->assertEquals($this->librarian, $this->user->getLibrarian());
    }

    public function testGetInstitution()
    {
        $this->user->setInstitution($this->institution);

        $this->assertEquals($this->institution, $this->user->getInstitution());
    }

    public function testGetCreatedMessages()
    {
        $this->user->addCreatedMessages($this->createdMessage);

        $this->assertContains($this->createdMessage, $this->user->getCreatedMessages());
    }

    public function testGetReceivedMessages()
    {
        $this->user->addReceivedMessages($this->receivedMessage);

        $this->assertContains($this->receivedMessage, $this->user->getReceivedMessages());
    }

    public function testGenerateUser()
    {
        $name = 'John';
        $surname = 'Doe';
        $birthdate = date('Y-m-d');
        $address = 'Calle x nro 123';

        $this->user->setName($name);
        $this->user->setSurname($surname);
        $this->user->setBirthdate($birthdate);
        $this->user->setAddress($address);

        $this->assertNull($this->user->getId());

        $this->documentManager->persist($this->user);
        $this->documentManager->flush();

        $this->assertNotNull($this->user->getId());
    }

    public function tearDown()
    {
        parent::tearDown();

        unset($this->user, $this->order, $this->operatedOrder, $this->createdOrder, $this->instance, $this->librarian, $this->institution, $this->createdMessage, $this->receivedMessage);
    }

}