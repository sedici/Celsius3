<?php

namespace Celsius\Celsius3Bundle\Tests\Document;

class SingleInstanceTest extends EventTest
{

    public function setUp()
    {
        parent::setUp();

        $this->event = $this->getMockForAbstractClass('Celsius\\Celsius3Bundle\\Document\\SingleInstance');
    }

}