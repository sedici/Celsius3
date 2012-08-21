<?php

namespace Celsius\Celsius3Bundle\Tests\Document;

use Celsius\Celsius3Bundle\Document\Admin;

class AdminTest extends UserTest
{

    public function setUp()
    {
        parent::setUp();

        $this->user = new Admin();
    }

}