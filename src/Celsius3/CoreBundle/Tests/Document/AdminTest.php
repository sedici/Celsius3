<?php

namespace Celsius3\CoreBundle\Tests\Document;

use Celsius3\CoreBundle\Document\Admin;

class AdminTest extends UserTest
{

    public function setUp()
    {
        parent::setUp();

        $this->user = new Admin();
    }

}
