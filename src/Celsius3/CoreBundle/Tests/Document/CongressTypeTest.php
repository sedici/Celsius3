<?php

namespace Celsius3\CoreBundle\Tests\Document;

use Celsius3\CoreBundle\Document\CongressType;

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
