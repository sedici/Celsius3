<?php

namespace Celsius3\CoreBundle\Tests\Document;

use Celsius3\CoreBundle\Tests\TestCase;
use Celsius3\CoreBundle\Document\Message;
use Celsius3\CoreBundle\Document\BaseUser;

class MessageTest extends TestCase
{
    protected $message;
    protected $sender;
    protected $receiver;

    public function setUp()
    {
        parent::setUp();

        $this->message = new Message();
        $this->sender = new BaseUser();
        $this->receiver = new BaseUser();
    }

    public function testGetSubject()
    {
        $subject = 'Some subject';

        $this->message->setSubject($subject);

        $this->assertEquals($subject, $this->message->getSubject());
    }

    public function testGetText()
    {
        $text = 'Some text.';

        $this->message->setText($text);

        $this->assertEquals($text, $this->message->getText());
    }

    public function testGetDate()
    {
        $date = date('y-m-d H:i:s');

        $this->message->setDate($date);

        $this->assertEquals($date, $this->message->getDate());
    }

    public function testGetViewed()
    {
        $viewed = false;

        $this->message->setViewed($viewed);

        $this->assertEquals($viewed, $this->message->getViewed());
    }

    public function testGetSender()
    {
        $this->message->setSender($this->sender);

        $this->assertEquals($this->sender, $this->message->getSender());
    }

    public function testGetReceiver()
    {
        $this->message->setReceiver($this->receiver);

        $this->assertEquals($this->receiver, $this->message->getReceiver());
    }

    public function testGenerateContactType()
    {
        $subject = 'Some subject';
        $text = 'Some text.';
        $date = date('y-m-d H:i:s');
        $viewed = false;

        $this->message->setSubject($subject);
        $this->message->setText($text);
        $this->message->setDate($date);
        $this->message->setViewed($viewed);

        $this->assertNull($this->message->getId());

        $this->documentManager->persist($this->message);
        $this->documentManager->flush();

        $this->assertNotNull($this->message->getId());
    }

    public function tearDown()
    {
        parent::tearDown();

        unset($this->message);
        unset($this->sender);
        unset($this->receiver);
    }
}
