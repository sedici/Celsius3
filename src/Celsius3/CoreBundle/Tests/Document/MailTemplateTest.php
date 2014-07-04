<?php

namespace Celsius3\CoreBundle\Tests\Document;

use Celsius3\CoreBundle\Tests\TestCase;
use Celsius3\CoreBundle\Document\MailTemplate;
use Celsius3\CoreBundle\Document\Instance;

class MailTemplateTest extends TestCase
{

    protected $mailTemplate;
    protected $instance;

    public function setUp()
    {
        parent::setUp();

        $this->mailTemplate = new MailTemplate();
        $this->instance = new Instance();
    }

    public function testGetTitle()
    {
        $title = 'Sample title';

        $this->mailTemplate->setTitle($title);

        $this->assertEquals($title, $this->mailTemplate->getTitle());
    }

    public function testGetText()
    {
        $text = 'Sample text';

        $this->mailTemplate->setText($text);

        $this->assertEquals($text, $this->mailTemplate->getText());
    }

    public function testGetInstance()
    {
        $this->mailTemplate->setInstance($this->instance);

        $this->assertEquals($this->instance, $this->mailTemplate->getInstance());
    }

    public function testGenerateMailTemplate()
    {
        $title = 'Sample title';
        $text = 'Sample text';

        $this->mailTemplate->setTitle($title);
        $this->mailTemplate->setText($text);

        $this->assertNull($this->mailTemplate->getId());

        $this->documentManager->persist($this->mailTemplate);
        $this->documentManager->flush();

        $this->assertNotNull($this->mailTemplate->getId());
    }

    public function tearDown()
    {
        parent::tearDown();

        unset($this->mailTemplate, $this->instance);
    }

}
