<?php

namespace Celsius3\CoreBundle\Tests\Document;

use Celsius3\CoreBundle\Document\SingleInstanceDeliver;

class SingleInstanceDeliverTest extends SingleInstanceTest
{

    public function setUp()
    {
        parent::setUp();

        $this->event = new SingleInstanceDeliver();
    }

    public function testGenerateSingleInstanceDeliver()
    {
        $date = date('Y-m-d H:i:s');

        $this->event->setDate($date);

        $this->documentManager->persist($this->event);
        $this->documentManager->flush();

        $this->assertNotNull($this->event->getId());
    }

}
