<?php

namespace Celsius\Celsius3Bundle\Tests\Document;

use Celsius\Celsius3Bundle\Document\BaseUser;

class BaseUserTest extends UserTest
{

    public function setUp()
    {
        parent::setUp();

        $this->user = new BaseUser();
    }

}