<?php

namespace Celsius3\CoreBundle\Tests\Document;

use Celsius3\CoreBundle\Document\Superadmin;

class SuperadminTest extends UserTest
{

    public function setUp()
    {
        parent::setUp();

        $this->user = new Superadmin();
    }

}
