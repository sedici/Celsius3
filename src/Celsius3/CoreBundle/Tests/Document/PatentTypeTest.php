<?php

namespace Celsius3\CoreBundle\Tests\Document;

use Celsius3\CoreBundle\Document\PatentType;

class PatentTypeTest extends MaterialTypeTest
{

    public function setUp()
    {
        parent::setUp();

        $this->material = new PatentType();
    }

}
