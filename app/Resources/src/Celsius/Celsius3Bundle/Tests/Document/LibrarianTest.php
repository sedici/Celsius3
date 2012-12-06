<?php

namespace Celsius\Celsius3Bundle\Tests\Document;

use Celsius\Celsius3Bundle\Document\Librarian;
use Celsius\Celsius3Bundle\Document\BaseUser;

class LibrarianTest extends UserTest
{

    protected $subordinate;

    public function setUp()
    {
        parent::setUp();

        $this->user = new Librarian();
        $this->subordinate = new BaseUser();
    }

    public function testGetSubordinates()
    {
        $this->user->addSubordinates($this->subordinate);

        $this->assertContains($this->subordinate, $this->user->getSubordinates());
    }

}