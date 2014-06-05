<?php

namespace Celsius3\CoreBundle\DataFixtures\MongoDB;

use Faker\Factory;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Celsius3\CoreBundle\Document;
use Celsius3\CoreBundle\Manager\MaterialTypeManager;
use Celsius3\CoreBundle\Manager\OrderManager;
use Celsius3\CoreBundle\Manager\UserManager;

/**
 * Description of FixtureLoader
 *
 * @author agustin
 */
class InstanceLoader extends AbstractFixture implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface
{

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        $generator = Factory::create('en');
        $generator->seed(1113);

        $dbhelper = $this->container->get('celsius3_core.database_helper');

        $composer = $this->container->get('fos_message.composer');
        $sender = $this->container->get('fos_message.sender');

        $material_types = array(
            MaterialTypeManager::TYPE__JOURNAL => array(
                'class' => 'Celsius3\\CoreBundle\\Document\\JournalType',
                'fields' => array(
                    'setVolume' => function () use ($generator) {
                return $generator->randomNumber;
            },
                    'setNumber' => function () use ($generator) {
                return $generator->randomNumber;
            },
                    'setJournal' => function () use ($generator, $manager) {
                $random = $generator->numberBetween(0, count($manager->getRepository('Celsius3CoreBundle:Journal')->findAll()) - 1);

                return $manager
                                ->getRepository(
                                        'Celsius3CoreBundle:Journal')
                                ->createQueryBuilder()
                                ->limit(-1)
                                ->skip($random)
                                ->getQuery()
                                ->execute()
                                ->getNext();
            },
                ),
            ),
            MaterialTypeManager::TYPE__BOOK => array(
                'class' => 'Celsius3\\CoreBundle\\Document\\BookType',
                'fields' => array(
                    'setEditor' => function () use ($generator) {
                return $generator->company;
            },
                    'setChapter' => function () use ($generator) {
                return $generator->randomNumber;
            },
                    'setISBN' => function () use ($generator) {
                return $generator->randomNumber(8);
            },
                ),
            ),
            MaterialTypeManager::TYPE__PATENT => array(
                'class' => 'Celsius3\\CoreBundle\\Document\\PatentType',
                'fields' => array(),
            ),
            MaterialTypeManager::TYPE__CONGRESS => array(
                'class' => 'Celsius3\\CoreBundle\\Document\\CongressType',
                'fields' => array(
                    'setPlace' => function () use ($generator) {
                return $generator->address;
            },
                    'setCommunication' => function () use ($generator) {
                return $generator->text(500);
            },
                ),
            ),
            MaterialTypeManager::TYPE__THESIS => array(
                'class' => 'Celsius3\\CoreBundle\\Document\\ThesisType',
                'fields' => array(
                    'setDirector' => function () use ($generator) {
                return $generator->name;
            },
                    'setDegree' => function () use ($generator) {
                return $generator->randomElement(array('grado', 'posgrado'));
            },
                ),
            ),
        );

        $hive = $this->getReference('hive');

        /*
         * Carga de Instancias Legacy
         */
        for ($i = 0; $i < $generator->numberBetween(5, 20); $i++) {
            $instance = new Document\LegacyInstance();
            $instance->setName($generator->company);
            $instance->setAbbreviation(strtoupper($generator->word));
            $instance->setWebsite($generator->url);
            $instance->setEmail($generator->email);
            $instance->setEnabled(true);
            $instance->setHive($hive);
            $manager->persist($instance);

            $institution = $dbhelper->findRandomRecord('Celsius3CoreBundle:Institution');
            $institution->setCelsiusInstance($instance);
            $manager->persist($institution);
        }
        $manager->flush();

        /*
         * Carga de Instancias
         */
        for ($i = 0; $i < 10; $i++) {
            $instance = new Document\Instance();
            $instance->setName($generator->company);
            $instance->setAbbreviation(strtoupper($generator->word));
            $instance->setWebsite($generator->url);
            $instance->setEmail($generator->email);
            $instance->setUrl($generator->word);
            $instance->setEnabled(true);
            $instance->setHive($hive);
            $manager->persist($instance);
            $manager->flush();

            $institution = $dbhelper->findRandomRecord('Celsius3CoreBundle:Institution');
            $institution->setCelsiusInstance($instance);
            $manager->persist($institution);

            for ($j = 0; $j < 3; $j++) {
                $template = new Document\MailTemplate();
                $template->setCode('tpl' . $j);
                $template->setEnabled(true);
                $template->setInstance($instance);
                $template->setText($generator->text);
                $template->setTitle($generator->sentence);
                $manager->persist($template);
                unset($template);
            }

            /*
             * Carga de catalogos por instancia
             */
            for ($j = 0; $j < $generator->numberBetween(5, 20); $j++) {
                $catalog = new Document\Catalog();
                $catalog->setName($generator->company);
                $catalog->setUrl($generator->url);
                $catalog->setInstance($instance);
                $manager->persist($catalog);
                unset($catalog);
            }

            /*
             * Creacion de un superadmin por instancia
             */
            $superadmin = new Document\BaseUser();
            $superadmin->setName('superadmin' . $i);
            $superadmin->setSurname('superadmin' . $i);
            $superadmin->setBirthdate($generator->date('Y-m-d'));
            $superadmin->setUsername('superadmin' . $i);
            $superadmin->setPlainPassword('superadmin' . $i);
            $superadmin->setEmail($generator->email);
            $superadmin->setAddress($generator->address);
            $superadmin->setInstance($instance);
            $superadmin->setEnabled(true);
            $superadmin->addRole(UserManager::ROLE_SUPER_ADMIN);
            $superadmin->setInstitution($institution);
            $manager->persist($superadmin);

            /*
             * Creacion de un admin por instancia
             */
            $admin = new Document\BaseUser();
            $admin->setName('admin' . $i);
            $admin->setSurname('admin' . $i);
            $admin->setBirthdate($generator->date('Y-m-d'));
            $admin->setUsername('admin' . $i);
            $admin->setPlainPassword('admin' . $i);
            $admin->setEmail($generator->email);
            $admin->setAddress($generator->address);
            $admin->setInstance($instance);
            $admin->setEnabled(true);
            $admin->addRole(UserManager::ROLE_ADMIN);
            $admin->setInstitution($institution);
            $manager->persist($admin);

            /*
             * Creacion de usuarios de cada instancia
             */
            for ($j = 0; $j < $generator->numberBetween(5, 20); $j++) {
                $user = new Document\BaseUser();
                $user->setName($generator->firstName);
                $user->setSurname($generator->lastName);
                $user->setBirthdate($generator->date('Y-m-d'));
                $user->setUsername($user->getName() . '_' . $generator->md5);
                $user->setPlainPassword($user->getName());
                $user->setEmail($generator->email);
                $user->setAddress($generator->address);
                $user->setInstance($instance);
                $user->setInstitution($institution);
                $manager->persist($user);

                /*
                 * Creacion de pedidos por instancia
                 */
                $material_keys = array_keys($material_types);
                for ($k = 0; $k < $generator->numberBetween(1, 5); $k++) {
                    $random_material = $generator->numberBetween(0, count($material_keys) - 1);
                    $material_type = $material_types[$material_keys[$random_material]];
                    $order = new Document\Order();

                    $material = new $material_type['class'];
                    $material->setAuthors($generator->name);
                    $material->setEndPage($generator->numberBetween(10, 1000));
                    $material->setStartPage($generator->numberBetween(1, $material->getEndPage()));
                    $material->setTitle(str_replace('.', '', $generator->sentence));
                    $material->setYear($generator->year);

                    foreach ($material_type['fields'] as $method => $function) {
                        $material->$method($function());
                    }

                    $order->setMaterialData($material);

                    $order->setOriginalRequest($this->container->get('celsius3_core.lifecycle_helper')->createRequest($order, $user, OrderManager::TYPE__SEARCH, $instance));

                    $manager->persist($order);

                    unset($order, $material, $random_material, $material_type, $request);
                }
                unset($material_keys);

                /*
                 * Creacion de mensajes
                 */
                $message1 = $composer->newThread()->setSender($user)
                                ->addRecipient($admin)
                                ->setSubject(str_replace('.', '', $generator->sentence))
                                ->setBody($generator->text(1000))->getMessage();

                $sender->send($message1);

                $message2 = $composer->reply($message1->getThread())
                        ->setSender($admin)->setBody($generator->text(1000))
                        ->getMessage();

                $sender->send($message2);

                unset($user, $message1, $message2);
            }

            /*
             * Creacion de noticias por instancia
             */
            for ($j = 0; $j < 20; $j++) {
                $news = new Document\News();
                $news->setDate($generator->date('Y-m-d H:i:s'));
                $news->setTitle(str_replace('.', '', $generator->sentence));
                $news->setText($generator->text(2000));
                $news->setInstance($instance);
                $manager->persist($news);
                unset($news);
            }
            $manager->flush();
            unset($instance, $admin);
        }
        $manager->clear();
    }

    public function getOrder()
    {
        return 4;
    }

}
