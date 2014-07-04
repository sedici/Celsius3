<?php

namespace Celsius3\CoreBundle\Tests\Document;

use Celsius3\CoreBundle\Document\BaseUser;

class BaseUserTest extends UserTest
{

    public function setUp()
    {
        parent::setUp();

        $this->user = new BaseUser();
    }

}
