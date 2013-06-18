<?php

namespace Celsius\Celsius3Bundle\Tests\Document;

use Celsius\Celsius3Bundle\Document\Superadmin;

class SuperadminTest extends UserTest
{

    public function setUp()
    {
        parent::setUp();

        $this->user = new Superadmin();
    }

}