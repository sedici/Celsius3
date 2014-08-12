<?php
/*
 * Celsius3 - Order management
 * Copyright (C) 2014 PrEBi <info@prebi.unlp.edu.ar>
 *
 * This file is part of Celsius3.
 *
 * Celsius3 is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Celsius3 is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Celsius3.  If not, see <http://www.gnu.org/licenses/>.
 */

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