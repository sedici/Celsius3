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
use Celsius3\CoreBundle\Document\Instance;
use Celsius3\CoreBundle\Document\BaseUser;
use Celsius3\CoreBundle\Document\Order;
use Celsius3\CoreBundle\Document\News;
use Celsius3\CoreBundle\Document\Contact;
use Celsius3\CoreBundle\Document\Institution;
use Celsius3\CoreBundle\Document\MailTemplate;
use Celsius3\CoreBundle\Document\Configuration;
use Celsius3\CoreBundle\Document\Catalog;
use Celsius3\CoreBundle\Document\Notification;
use Celsius3\CoreBundle\Document\Creation;
use Celsius3\CoreBundle\Document\State;

class InstanceTest extends TestCase
{
    protected $instance;
    protected $user;
    protected $order;
    protected $news;
    protected $contact;
    protected $institution;
    protected $template;
    protected $configuration;
    protected $catalog;
    protected $notification;
    protected $event;
    protected $state;

    public function setUp()
    {
        parent::setUp();

        $this->instance = new Instance();
        $this->user = new BaseUser();
        $this->order = new Order();
        $this->news =  new News();
        $this->contact = new Contact();
        $this->institution = new Institution();
        $this->template = new MailTemplate();
        $this->configuration = new Configuration();
        $this->catalog = new Catalog();
        $this->notification = new Notification();
        $this->event = new Creation();
        $this->state = new State();
    }

    public function testToString()
    {
        $name = 'Instance Name';

        $this->instance->setName($name);

        $this->assertEquals($name, $this->instance->__toString());
    }

    public function testGetName()
    {
        $name = 'Instance Name';

        $this->instance->setName($name);

        $this->assertEquals($name, $this->instance->getName());
    }

    public function testGetAbbreviation()
    {
        $abbreviation = 'IN';

        $this->instance->setAbbreviation($abbreviation);

        $this->assertEquals($abbreviation, $this->instance->getAbbreviation());
    }

    public function testGetUrl()
    {
        $url = 'sampleurl';

        $this->instance->setUrl($url);

        $this->assertEquals($url, $this->instance->getUrl());
    }

    public function testGetWebsite()
    {
        $website = 'http://instance.website.com';

        $this->instance->setWebsite($website);

        $this->assertEquals($website, $this->instance->getWebsite());
    }

    public function testGetEmail()
    {
        $email = 'instance@email.com';

        $this->instance->setEmail($email);

        $this->assertEquals($email, $this->instance->getEmail());
    }

    public function testGetEnabled()
    {
        $enabled = true;

        $this->instance->setEnabled($enabled);

        $this->assertEquals($enabled, $this->instance->getEnabled());
    }

    public function testGet()
    {
        $this->configuration->setKey('key');
        $this->configuration->setValue('value');

        $this->instance->addConfigurations($this->configuration);

        $this->assertEquals($this->configuration, $this->instance->get('key'));
    }

    public function testGetUsers()
    {
        $this->instance->addUsers($this->user);

        $this->assertContains($this->user, $this->instance->getUsers());
    }

    public function testGetOrders()
    {
        $this->instance->addOrders($this->order);

        $this->assertContains($this->order, $this->instance->getOrders());
    }

    public function testGetNews()
    {
        $this->instance->addNews($this->news);

        $this->assertContains($this->news, $this->instance->getNews());
    }

    public function testGetContacts()
    {
        $this->instance->addContacts($this->contact);

        $this->assertContains($this->contact, $this->instance->getContacts());
    }

    public function testGetInstitutions()
    {
        $this->instance->addInstitutions($this->institution);

        $this->assertContains($this->institution, $this->instance->getInstitutions());
    }

    public function testGetTemplates()
    {
        $this->instance->addTemplates($this->template);

        $this->assertContains($this->template, $this->instance->getTemplates());
    }

    public function testGetConfigurations()
    {
        $this->instance->addConfigurations($this->configuration);

        $this->assertContains($this->configuration, $this->instance->getConfigurations());
    }

    public function testGetCatalogs()
    {
        $this->instance->addCatalogs($this->catalog);

        $this->assertContains($this->catalog, $this->instance->getCatalogs());
    }

    public function testGetNotifications()
    {
        $this->instance->addNotifications($this->notification);

        $this->assertContains($this->notification, $this->instance->getNotifications());
    }

    public function testGetEvents()
    {
        $this->instance->addEvents($this->event);

        $this->assertContains($this->event, $this->instance->getEvents());
    }

    public function testGetStates()
    {
        $this->instance->addStates($this->state);

        $this->assertContains($this->state, $this->instance->getStates());
    }

    public function testGenerateInstance()
    {
        $name = 'Instance Name';
        $abbreviation = 'IN';
        $url = 'sampleurl';
        $website = 'http://instance.website.com';
        $email = 'instance@email.com';

        $this->instance->setName($name);
        $this->instance->setAbbreviation($abbreviation);
        $this->instance->setUrl($url);
        $this->instance->setWebsite($website);
        $this->instance->setEmail($email);

        $this->assertNull($this->instance->getId());

        $this->documentManager->persist($this->instance);
        $this->documentManager->flush();

        $this->assertNotNull($this->instance->getId());
    }

    public function tearDown()
    {
        parent::tearDown();

        unset($this->instance, $this->user, $this->order, $this->news, $this->contact,
                $this->institution, $this->template, $this->configuration, $this->catalog,
                $this->notification, $this->event, $this->state);
    }
}