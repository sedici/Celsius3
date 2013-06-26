<?php

namespace Celsius3\CoreBundle\DataFixtures\MongoDB;
use Faker\Factory;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Celsius3\CoreBundle\Document;
use Celsius3\CoreBundle\Manager\StateManager;
use Celsius3\CoreBundle\Manager\MaterialTypeManager;
use Celsius3\CoreBundle\Manager\OrderManager;
use Celsius3\CoreBundle\Manager\UserManager;

/**
 * Description of FixtureLoader
 *
 * @author agustin
 */
class FixtureLoader implements FixtureInterface, ContainerAwareInterface
{

    private $container;
    private $configurations = array(
            'instance_title' => array('name' => 'Title',
                    'value' => 'Default title', 'type' => 'string',),
            'results_per_page' => array('name' => 'Results per page',
                    'value' => '10', 'type' => 'integer',),
            'email_reply_address' => array('name' => 'Reply to',
                    'value' => 'sample@instance.edu', 'type' => 'email',),
            'instance_description' => array('name' => 'Instance description',
                    'value' => '', 'type' => 'text',),
            'default_language' => array('name' => 'Default language',
                    'value' => 'es', 'type' => 'language',),
            'confirmation_type' => array('name' => 'Confirmation type',
                    'value' => 'email', 'type' => 'confirmation',),
            'mail_signature' => array('name' => 'Mail signature',
                    'value' => '', 'type' => 'text'),);
    private $state_types = array(StateManager::STATE__CREATED,
            StateManager::STATE__SEARCHED, StateManager::STATE__REQUESTED,
            StateManager::STATE__APPROVAL_PENDING,
            StateManager::STATE__RECEIVED, StateManager::STATE__DELIVERED,
            StateManager::STATE__CANCELLED, StateManager::STATE__ANNULLED,);

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        $generator = Factory::create('es_AR');
        $generator->seed(1112);

        $composer = $this->container->get('fos_message.composer');
        $sender = $this->container->get('fos_message.sender');

        $material_types = array(
                MaterialTypeManager::TYPE__JOURNAL => array(
                        'class' => 'Celsius3\\CoreBundle\\Document\\JournalType',
                        'fields' => array(
                                'setVolume' => function () use ($generator)
                                {
                                    return $generator->randomNumber;
                                },
                                'setNumber' => function () use ($generator)
                                {
                                    return $generator->randomNumber;
                                },
                                'setJournal' => function () use ($generator,
                                        $manager)
                                {
                                    $random = $generator
                                            ->randomNumber(0,
                                                    $manager
                                                            ->getRepository(
                                                                    'Celsius3CoreBundle:Journal')
                                                            ->findAll()
                                                            ->count() - 1);

                                    return $manager
                                            ->getRepository(
                                                    'Celsius3CoreBundle:Journal')
                                            ->createQueryBuilder()->limit(-1)
                                            ->skip($random)->getQuery()
                                            ->execute()->getNext();
                                },),),
                MaterialTypeManager::TYPE__BOOK => array(
                        'class' => 'Celsius3\\CoreBundle\\Document\\BookType',
                        'fields' => array(
                                'setEditor' => function () use ($generator)
                                {
                                    return $generator->company;
                                },
                                'setChapter' => function () use ($generator)
                                {
                                    return $generator->randomNumber;
                                },
                                'setISBN' => function () use ($generator)
                                {
                                    return $generator->randomNumber(13);
                                },),),
                MaterialTypeManager::TYPE__PATENT => array(
                        'class' => 'Celsius3\\CoreBundle\\Document\\PatentType',
                        'fields' => array(),),
                MaterialTypeManager::TYPE__CONGRESS => array(
                        'class' => 'Celsius3\\CoreBundle\\Document\\CongressType',
                        'fields' => array(
                                'setPlace' => function () use ($generator)
                                {
                                    return $generator->address;
                                },
                                'setCommunication' => function () use (
                                        $generator)
                                {
                                    return $generator->text(500);
                                },),),
                MaterialTypeManager::TYPE__THESIS => array(
                        'class' => 'Celsius3\\CoreBundle\\Document\\ThesisType',
                        'fields' => array(
                                'setDirector' => function () use ($generator)
                                {
                                    return $generator->name;
                                },
                                'setDegree' => function () use ($generator)
                                {
                                    return $generator
                                            ->randomElement(
                                                    array('grado', 'posgrado'));
                                },),),);

        foreach ($this->configurations as $key => $data) {
            $configuration = new Document\Configuration();
            $configuration->setName($data['name']);
            $configuration->setKey($key);
            $configuration->setValue($data['value']);
            $configuration->setType($data['type']);
            $manager->persist($configuration);
            unset($configuration);
        }
        $manager->flush();

        foreach ($this->state_types as $key => $value) {
            $state_type = new Document\StateType();
            $state_type->setName($value);
            $state_type->setPosition($key);
            $manager->persist($state_type);
            unset($state_type);
        }
        $manager->flush();

        /*
         * Migracion de PIDU
         */
        $this->container->get('celsius3_migration.migration_manager')
                ->migrate($this->container->getParameter('celsius2_host'),
                        $this->container->getParameter('celsius2_username'),
                        $this->container->getParameter('celsius2_password'),
                        $this->container->getParameter('celsius2_database'),
                        $this->container->getParameter('celsius2_port'),
                        $manager);

        for ($i = 0; $i < 100; $i++) {
            $journal = new Document\Journal();
            $journal->setName(str_replace('.', '', $generator->sentence));
            $journal->setAbbreviation(strtoupper($generator->word));
            $journal->setISSN($generator->randomNumber(8));
            $journal->setISSNE($generator->bothify('#######X'));
            $journal
                    ->setFrecuency(
                            $generator
                                    ->randomElement(
                                            array('anual', 'semestral',
                                                    'mensual')));
            $journal->setResponsible($generator->name);
            $manager->persist($journal);
            unset($journal);
        }
        $manager->flush();
        $manager->clear();

        for ($i = 0; $i < 10; $i++) {
            $template = new Document\MailTemplate();
            $template->setCode('tpl' . $i);
            $template->setEnabled(true);
            $template->setText($generator->text);
            $template->setTitle($generator->sentence);
            $manager->persist($template);
            unset($template);
        }

        /*
         * Carga de catalogos globales
         */
        for ($i = 0; $i < 50; $i++) {
            $catalog = new Document\Catalog();
            $catalog->setName($generator->company);
            $catalog->setUrl($generator->url);
            $manager->persist($catalog);
            unset($catalog);
        }
        $manager->flush();
        $manager->clear();

        /*
         * Carga de Instancias
         */
        for ($i = 0; $i < 5; $i++) {
            $instance = new Document\Instance();
            $instance->setName($generator->company);
            $instance->setAbbreviation(strtoupper($generator->word));
            $instance->setWebsite($generator->url);
            $instance->setEmail($generator->email);
            $instance->setUrl($generator->word);
            $instance->setEnabled(true);
            $manager->persist($instance);
            $manager->flush();

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
            for ($j = 0; $j < 50; $j++) {
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
            $admin->addRole(UserManager::ROLE_SUPER_ADMIN);
            $manager->persist($admin);

            /*
             * Creacion de usuarios de cada instancia
             */
            for ($j = 0; $j < 20; $j++) {
                $user = new Document\BaseUser();
                $user->setName($generator->firstName);
                $user->setSurname($generator->lastName);
                $user->setBirthdate($generator->date('Y-m-d'));
                $user->setUsername($user->getName() . '_' . $generator->md5);
                $user->setPlainPassword($user->getName());
                $user->setEmail($generator->email);
                $user->setAddress($generator->address);
                $user->setInstance($instance);
                $manager->persist($user);

                /*
                 * Creacion de pedidos por instancia
                 */
                foreach ($material_types as $material_type) {
                    $order = new Document\Order();
                    $order->setOwner($user);
                    $order->setType(OrderManager::TYPE__SEARCH);
                    $order->setInstance($instance);

                    $material = new $material_type['class'];
                    $material->setAuthors($generator->name);
                    $material->setEndPage($generator->randomNumber);
                    $material
                            ->setStartPage(
                                    $generator
                                            ->randomNumber(1,
                                                    $material->getEndPage()));
                    $material
                            ->setTitle(
                                    str_replace('.', '', $generator->sentence));
                    $material->setYear($generator->year);

                    foreach ($material_type['fields'] as $method => $function) {
                        $material->$method($function());
                    }

                    $order->setMaterialData($material);

                    $manager->persist($order);
                    unset($order, $material);
                }

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
    }
}