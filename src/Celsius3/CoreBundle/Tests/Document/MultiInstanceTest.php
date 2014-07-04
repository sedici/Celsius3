<?php

namespace Celsius3\CoreBundle\Tests\Document;

use Celsius3\CoreBundle\Document\Instance;

class MultiInstanceTest extends EventTest
{

    protected $remoteInstance;

    public function setUp()
    {
        parent::setUp();

        $this->event = $this->getMockForAbstractClass('Celsius\\Celsius3Bundle\\Document\\MultiInstance');
        $this->remoteInstance = new Instance();
    }

    public function testGetRemoteInstance()
    {
        $this->event->setRemoteInstance($this->remoteInstance);

        $this->assertEquals($this->remoteInstance, $this->event->getRemoteInstance());
    }

    public function testNotifyRemoteInstance()
    {
        $this->assertNull($this->event->notifyRemoteInstance());
    }

    public function tearDown()
    {
        parent::tearDown();

        unset($this->remoteInstance);
    }

}
