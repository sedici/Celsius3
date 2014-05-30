<?php

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
