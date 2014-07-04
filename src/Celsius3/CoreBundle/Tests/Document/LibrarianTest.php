<?php

namespace Celsius3\CoreBundle\Tests\Document;

use Celsius3\CoreBundle\Document\Librarian;
use Celsius3\CoreBundle\Document\BaseUser;

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
