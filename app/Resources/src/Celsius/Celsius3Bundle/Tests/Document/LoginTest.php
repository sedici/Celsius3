<?php

namespace Celsius\Celsius3Bundle\Tests\Document;

use Celsius\Celsius3Bundle\Tests\TestCase;
use Celsius\Celsius3Bundle\Document\Login;

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