<?php

namespace Celsius\Celsius3Bundle\Tests\Document;

use Celsius\Celsius3Bundle\Document\MultiInstanceRequest;
use Celsius\Celsius3Bundle\Document\State;

class MultiInstanceRequestTest extends MultiInstanceTest
{

    protected $remoteState;

    public function setUp()
    {
        parent::setUp();

        $this->event = new MultiInstanceRequest();
        $this->remoteState = new State();
    }

    public function testGetRemoteInstance()
    {
        $this->event->setRemoteState($this->remoteState);

        $this->assertEquals($this->remoteState, $this->event->getRemoteState());
    }
    
    public function testGenerateMultiInstanceRequest()
    {
        $date = date('Y-m-d H:i:s');

        $this->event->setDate($date);

        $this->documentManager->persist($this->event);
        $this->documentManager->flush();

        $this->assertNotNull($this->event->getId());
    }

    public function tearDown()
    {
        parent::tearDown();

        unset($this->remoteState);
    }

}