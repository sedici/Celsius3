<?php

namespace Celsius\Celsius3Bundle\Tests\Document;

use Celsius\Celsius3Bundle\Document\CongressType;

class CongressTypeTest extends MaterialTypeTest
{

    public function setUp()
    {
        parent::setUp();

        $this->material = new CongressType();
    }

    public function testGetPlate()
    {
        $place = 'Sample place';

        $this->material->setPlace($place);

        $this->assertEquals($place, $this->material->getPlace());
    }

    public function testGetCommunication()
    {
        $communication = 'Sample communication';

        $this->material->setCommunication($communication);

        $this->assertEquals($communication, $this->material->getCommunication());
    }

}