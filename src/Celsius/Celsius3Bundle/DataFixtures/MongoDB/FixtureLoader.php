<?php

namespace Celsius\Celsius3Bundle\DataFixtures\MongoDB;

use Faker\Factory;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Celsius\Celsius3Bundle\Document;
use Celsius\Celsius3Bundle\Manager\StateManager;

/**
 * Description of FixtureLoader
 *
 * @author agustin
 */
class FixtureLoader implements FixtureInterface
{

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
    );
    private $state_types = array(
        StateManager::STATE__CREATED,
        StateManager::STATE__SEARCHED,
        StateManager::STATE__REQUESTED,
        StateManager::STATE__APPROVAL_PENDING,
        StateManager::STATE__RECEIVED,
        StateManager::STATE__DELIVERED,
        StateManager::STATE__CANCELED,
        StateManager::STATE__ANNULED,
    );

    public function load(ObjectManager $manager)
    {
        $generator = Factory::create('es_AR');
        $generator->seed(1111);

        $material_types = array(
            'journal' => array(
                'class' => 'Celsius\\Celsius3Bundle\\Document\\JournalType',
                'fields' => array(
                    'setVolume' => function() use ($generator)
                    {
                        return $generator->randomNumber;
                    },
                    'setNumber' => function() use ($generator)
                    {
                        return $generator->randomNumber;
                    },
                    'setJournal' => function() use ($generator, $manager)
                    {
                        $random = $generator->randomNumber(0, $manager->getRepository('CelsiusCelsius3Bundle:Journal')->findAll()->count()-1);

                        return $manager->getRepository('CelsiusCelsius3Bundle:Journal')
                                        ->createQueryBuilder()
                                        ->limit(-1)
                                        ->skip($random)
                                        ->getQuery()
                                        ->execute()
                                        ->getNext();
                    },
                ),
            ),
            'book' => array(
                'class' => 'Celsius\\Celsius3Bundle\\Document\\BookType',
                'fields' => array(
                    'setEditor' => function() use ($generator)
                    {
                        return $generator->company;
                    },
                    'setChapter' => function() use ($generator)
                    {
                        return $generator->randomNumber;
                    },
                    'setISBN' => function() use ($generator)
                    {
                        return $generator->randomNumber(13);
                    },
                ),
            ),
            'patent' => array(
                'class' => 'Celsius\\Celsius3Bundle\\Document\\PatentType',
                'fields' => array(),
            ),
            'congress' => array(
                'class' => 'Celsius\\Celsius3Bundle\\Document\\CongressType',
                'fields' => array(
                    'setPlace' => function() use ($generator)
                    {
                        return $generator->address;
                    },
                    'setCommunication' => function() use ($generator)
                    {
                        return $generator->text(500);
                    },
                ),
            ),
            'thesis' => array(
                'class' => 'Celsius\\Celsius3Bundle\\Document\\ThesisType',
                'fields' => array(
                    'setDirector' => function() use ($generator)
                    {
                        return $generator->name;
                    },
                    'setDegree' => function() use ($generator)
                    {
                        return $generator->randomElement(array('grado', 'posgrado'));
                    },
                ),
            ),
        );

        foreach ($this->configurations as $key => $data)
        {
            $configuration = new Document\Configuration();
            $configuration->setName($data['name']);
            $configuration->setKey($key);
            $configuration->setValue($data['value']);
            $configuration->setType($data['type']);
            $manager->persist($configuration);
            unset($configuration);
        }
        $manager->flush();

        foreach ($this->state_types as $key => $value)
        {
            $state_type = new Document\StateType();
            $state_type->setName($value);
            $state_type->setPosition($key);
            $manager->persist($state_type);
            unset($state_type);
        }
        $manager->flush();

        for ($i = 0; $i < 100; $i++)
        {
            $journal = new Document\Journal();
            $journal->setName(str_replace('.', '', $generator->sentence));
            $journal->setAbbreviation(strtoupper($generator->word));
            $journal->setISSN($generator->randomNumber(8));
            $journal->setISSNE($generator->bothify('#######X'));
            $journal->setFrecuency($generator->randomElement(array('anual', 'semestral', 'mensual')));
            $journal->setResponsible($generator->name);
            $manager->persist($journal);
            unset($journal);
        }
        $manager->flush();

        for ($i = 0; $i < 5; $i++)
        {
            $instance = new Document\Instance();
            $instance->setName($generator->company);
            $instance->setAbbreviation(strtoupper($generator->word));
            $instance->setWebsite($generator->url);
            $instance->setEmail($generator->email);
            $instance->setUrl($generator->word);
            $instance->setEnabled(true);
            $manager->persist($instance);
            $manager->flush();

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
            $admin->addRole('ROLE_SUPER_ADMIN');
            $manager->persist($admin);

            for ($j = 0; $j < 20; $j++)
            {
                $user = new Document\BaseUser();
                $user->setName($generator->firstName);
                $user->setSurname($generator->lastName);
                $user->setBirthdate($generator->date('Y-m-d'));
                $user->setUsername($user->getName() . '_' . $generator->md5);
                $user->setPlainPassword($generator->md5);
                $user->setEmail($generator->email);
                $user->setAddress($generator->address);
                $user->setInstance($instance);
                $manager->persist($user);

                foreach ($material_types as $material_type)
                {
                    $order = new Document\Order();
                    $order->setOwner($user);
                    $order->setType(1);
                    $order->setInstance($instance);

                    $material = new $material_type['class'];
                    $material->setAuthors($generator->name);
                    $material->setEndPage($generator->randomNumber);
                    $material->setStartPage($generator->randomNumber(1,$material->getEndPage()));
                    $material->setTitle(str_replace('.', '', $generator->sentence));
                    $material->setYear($generator->year);

                    foreach ($material_type['fields'] as $method => $function)
                    {
                        $material->$method($function());
                    }

                    $order->setMaterialData($material);

                    $manager->persist($order);
                    unset($material, $order);
                }
                unset($user);
            }

            for ($j = 0; $j < 20; $j++)
            {
                $news = new Document\News();
                $news->setDate($generator->date('Y-m-d H:i:s'));
                $news->setTitle(str_replace('.', '', $generator->sentence));
                $news->setText($generator->text(500));
                $news->setInstance($instance);
                $manager->persist($news);
                unset($news);
            }
            $manager->flush();
            $manager->clear();
            unset($instance);
        }
    }

}