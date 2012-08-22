<?php

namespace Celsius\Celsius3Bundle\Tests\Document;

use Celsius\Celsius3Bundle\Document\SingleInstanceRequest;

class SingleInstanceRequestTest extends SingleInstanceTest
{

    public function setUp()
    {
        parent::setUp();

        $this->event = new SingleInstanceRequest();
    }
    
    public function testGenerateSingleInstanceRequest()
    {
        $date = date('Y-m-d H:i:s');

        $this->event->setDate($date);

        $this->documentManager->persist($this->event);
        $this->documentManager->flush();

        $this->assertNotNull($this->event->getId());
    }

}