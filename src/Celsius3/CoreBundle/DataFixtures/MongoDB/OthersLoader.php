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

namespace Celsius3\CoreBundle\DataFixtures\MongoDB;

use Faker\Factory;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Celsius3\CoreBundle\Document;

/**
 * Description of FixtureLoader
 *
 * @author agustin
 */
class OthersLoader extends AbstractFixture implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface
{

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        $generator = Factory::create('en');
        $generator->seed(1113);

        /*
         * Carga de contactos
         */
//        $contact_types = $manager->getRepository('Celsius3CoreBundle:ContactType')
//                ->findAll();
//        $institutions = $manager->getRepository('Celsius3CoreBundle:Institution')
//                ->findAll();
//
//        foreach ($institutions as $institution) {
//            foreach ($contact_types as $contact_type) {
//                $contact = new Document\Contact();
//                $contact->setName($generator->firstName);
//                $contact->setSurname($generator->lastName);
//                $contact->setEmail($generator->email);
//                $contact->setAddress($generator->address);
//                $contact->setType($contact_type);
//                $contact->setInstitution($institution);
//                $manager->persist($contact);
//                unset($contact);
//            }
//        }
//        $manager->flush();
//        $manager->clear();
    }

    public function getOrder()
    {
        return 5;
    }

}