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

namespace Celsius3\CoreBundle\DataFixtures\ORM;

use Faker\Factory;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Celsius3\CoreBundle\Entity;
use Celsius3\CoreBundle\Manager\StateManager;
use Celsius3\CoreBundle\Manager\InstanceManager;

/**
 * Description of FixtureLoader
 *
 * @author agustin
 */
class DirectoryLoader extends AbstractFixture implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface
{
    private $container;
    private $directory_configurations = array(
        'instance_title' => array(
            'name' => 'Title',
            'value' => 'Celsius3 Directory',
            'type' => 'string',
        ),
        'results_per_page' => array(
            'name' => 'Results per page',
            'value' => '10',
            'type' => 'integer',
        ),
        'instance_description' => array(
            'name' => 'Instance description',
            'value' => '',
            'type' => 'text',
        ),
        'default_language' => array(
            'name' => 'Default language',
            'value' => 'es',
            'type' => 'language',
        ),
    );
    private $configurations = array(
        'instance_title' => array(
            'name' => 'Title',
            'value' => 'Default title',
            'type' => 'string',
        ),
        'results_per_page' => array(
            'name' => 'Results per page',
            'value' => '10',
            'type' => 'integer',
        ),
        'email_reply_address' => array(
            'name' => 'Reply to',
            'value' => 'sample@instance.edu',
            'type' => 'email',
        ),
        'instance_description' => array(
            'name' => 'Instance description',
            'value' => '',
            'type' => 'text',
        ),
        'default_language' => array(
            'name' => 'Default language',
            'value' => 'es',
            'type' => 'language',
        ),
        'confirmation_type' => array(
            'name' => 'Confirmation type',
            'value' => 'email',
            'type' => 'confirmation',
        ),
        'mail_signature' => array(
            'name' => 'Mail signature',
            'value' => '',
            'type' => 'text',
        ),
        'api_key' => array(
            'name' => 'Api Key',
            'value' => '',
            'type' => 'string',
        ),
    );
    private $contact_types = array(
        'Director',
        'Librarian',
        'Technician',
    );
    private $state_types = array(
        StateManager::STATE__CREATED,
        StateManager::STATE__SEARCHED,
        StateManager::STATE__REQUESTED,
        StateManager::STATE__APPROVAL_PENDING,
        StateManager::STATE__RECEIVED,
        StateManager::STATE__DELIVERED,
        StateManager::STATE__CANCELLED,
        StateManager::STATE__ANNULLED,
        StateManager::STATE__TAKEN,
    );

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        $generator = Factory::create('en');
        $generator->seed(1113);

        /*
         * Instancia que representa al directorio
         */
        $directory = new Entity\Instance();
        $directory->setName('Directory');
        $directory->setAbbreviation('Directory');
        $directory->setWebsite('http://celsius3.prebi.unlp.edu.ar');
        $directory->setEmail('soporte@prebi.unlp.edu.ar');
        $directory->setUrl(InstanceManager::INSTANCE__DIRECTORY);
        $directory->setEnabled(false);
        $manager->persist($directory);
        $manager->flush();

        $this->addReference('directory', $directory);

        $hive = new Entity\Hive();
        $hive->setName('LibLink');
        $manager->persist($hive);
        $manager->flush();

        $this->addReference('hive', $hive);

        /*
         * Configuración del directorio
         */
        foreach ($this->directory_configurations as $key => $data) {
            $configuration = new Entity\Configuration();
            $configuration->setName($data['name']);
            $configuration->setKey($key);
            $configuration->setValue($data['value']);
            $configuration->setType($data['type']);
            $configuration->setInstance($directory);

            if ($key == 'instance_description') {
                $configuration->setValue($generator->text(1000));
            }

            $manager->persist($configuration);
            unset($configuration);
        }
        $manager->flush();

        /*
         * Configuración modelo para las Instancias
         */
        foreach ($this->configurations as $key => $data) {
            $configuration = new Entity\Configuration();
            $configuration->setName($data['name']);
            $configuration->setKey($key);
            $configuration->setValue($data['value']);
            $configuration->setType($data['type']);

            if ($key == 'instance_description') {
                $configuration->setValue($generator->text(1000));
            }

            $manager->persist($configuration);
            unset($configuration);
        }

        foreach ($this->contact_types as $contacttype) {
            $ct = new Entity\ContactType();
            $ct->setName($contacttype);
            $manager->persist($ct);
            unset($ct);
        }
    }

    public function getOrder()
    {
        return 1;
    }
}