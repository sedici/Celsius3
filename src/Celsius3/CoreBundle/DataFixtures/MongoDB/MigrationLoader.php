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

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Description of FixtureLoader
 *
 * @author agustin
 */
class MigrationLoader extends AbstractFixture implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface
{

    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        $dbhelper = $this->container->get('celsius3_core.database_helper');

        /*
         * Migracion de PIDU
         */
        $this->container->get('celsius3_migration.migration_manager')->migrate($this->container->getParameter('celsius2_host'), $this->container->getParameter('celsius2_username'), $this->container->getParameter('celsius2_password'), $this->container->getParameter('celsius2_database'), $this->container->getParameter('celsius2_port'), $manager);

        /*
         * Asignación de instituciones a catálogos
         */
        $catalogs = $manager->getRepository('Celsius3CoreBundle:Catalog')
                ->findAll();
        foreach ($catalogs as $catalog) {
            $catalog->setInstitution($dbhelper->findRandomRecord('Celsius3CoreBundle:Institution'));

            $manager->persist($catalog);
        }
        $manager->flush();
    }

    public function getOrder()
    {
        return 3;
    }

}