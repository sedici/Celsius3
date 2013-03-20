<?php

namespace Celsius\Celsius3Bundle\DataFixtures\MongoDB;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Celsius\Celsius3Bundle\Document;
use Celsius\Celsius3Bundle\Helper\LoremIpsumHelper;
use Celsius\Celsius3Bundle\Manager\StateManager;

/**
 * Description of FixtureLoader
 *
 * @author agustin
 */
class FixtureLoader implements FixtureInterface
{

    public function load(ObjectManager $manager)
    {
        $names = array('John', 'Tom', 'Laura', 'Mike', 'Chris', 'Michelle', 'Liz', 'Mary', 'Joe', 'Kevin');
        $surnames = array('Perez', 'Rodriguez', 'Dominguez', 'Martinez', 'Gonzalez', 'Ibañez', 'Fernandez', 'Ledesma', 'Acuña', 'Estevez');

        $generator = new LoremIpsumHelper();

        $min = strtotime('1940-01-01');
        $max = strtotime('2000-12-31');

        $configuration = new Document\Configuration();
        $configuration->setName('Title');
        $configuration->setKey('instance_title');
        $configuration->setValue('Default Title');
        $configuration->setType('string');
        $manager->persist($configuration);
        unset($configuration);

        $configuration = new Document\Configuration();
        $configuration->setName('Results per page');
        $configuration->setKey('results_per_page');
        $configuration->setValue('10');
        $configuration->setType('integer');
        $manager->persist($configuration);
        unset($configuration);

        $configuration = new Document\Configuration();
        $configuration->setName('Reply to');
        $configuration->setKey('email_reply_address');
        $configuration->setValue('sample@instance.edu');
        $configuration->setType('email');
        $manager->persist($configuration);
        unset($configuration);

        $configuration = new Document\Configuration();
        $configuration->setName('Instance description');
        $configuration->setKey('instance_description');
        $configuration->setType('text');
        $manager->persist($configuration);
        unset($configuration);

        $configuration = new Document\Configuration();
        $configuration->setName('Default language');
        $configuration->setKey('instance_language');
        $configuration->setType('language');
        $configuration->setValue('es');
        $manager->persist($configuration);
        unset($configuration);
        
        $configuration = new Document\Configuration();
        $configuration->setName('Confirmation type');
        $configuration->setKey('confirmation_type');
        $configuration->setValue('email');
        $configuration->setType('confirmation');
        $manager->persist($configuration);
        unset($configuration);

        $manager->flush();

        $state_type = new Document\StateType();
        $state_type->setName(StateManager::STATE__CREATED);
        $state_type->setPosition(0);
        $manager->persist($state_type);
        unset($state_type);

        $state_type = new Document\StateType();
        $state_type->setName(StateManager::STATE__SEARCHED);
        $state_type->setPosition(1);
        $manager->persist($state_type);
        unset($state_type);

        $state_type = new Document\StateType();
        $state_type->setName(StateManager::STATE__REQUESTED);
        $state_type->setPosition(2);
        $manager->persist($state_type);
        unset($state_type);
        
        $state_type = new Document\StateType();
        $state_type->setName(StateManager::STATE__APPROVAL_PENDING);
        $state_type->setPosition(3);
        $manager->persist($state_type);
        unset($state_type);

        $state_type = new Document\StateType();
        $state_type->setName(StateManager::STATE__RECEIVED);
        $state_type->setPosition(4);
        $manager->persist($state_type);
        unset($state_type);

        $state_type = new Document\StateType();
        $state_type->setName(StateManager::STATE__DELIVERED);
        $state_type->setPosition(5);
        $manager->persist($state_type);
        unset($state_type);

        $state_type = new Document\StateType();
        $state_type->setName(StateManager::STATE__CANCELED);
        $state_type->setPosition(6);
        $manager->persist($state_type);
        unset($state_type);

        $state_type = new Document\StateType();
        $state_type->setName(StateManager::STATE__ANNULED);
        $state_type->setPosition(7);
        $manager->persist($state_type);
        unset($state_type);

        $manager->flush();

        for ($i = 0; $i < 5; $i++)
        {
            $instance = new Document\Instance();
            $instance->setName(str_replace('. ', '', $generator->getContent(rand(1, 5), 'plain', false)));
            $instance->setAbbreviation(str_replace('. ', '', $generator->getContent(1, 'plain', false)));
            $instance->setWebsite('http://' . md5(rand(0, 99999999)) . '.test.com');
            $instance->setEmail(md5(rand(0, 99999999)) . '@test.com');
            $instance->setUrl(str_replace('. ', '', $i . '_' . $generator->getContent(1, 'plain', false)));
            $instance->setEnabled(true);
            $manager->persist($instance);
            $manager->flush();

            for ($j = 0; $j < 20; $j++)
            {
                $user = new Document\BaseUser();
                $user->setName($names[rand(0, 99999999) % 10]);
                $user->setSurname($surnames[rand(0, 99999999) % 10]);
                $user->setBirthdate(date('Y-m-d', mt_rand($min, $max)));
                $user->setUsername($user->getName() . '_' . md5(rand(0, 99999999)));
                $user->setPassword(md5(rand(0, 99999999)));
                $user->setEmail($user->getName() . $user->getSurname() . md5(rand(0, 99999999)) . $i . $j . '@test.com');
                $user->setAddress('address_' . md5(rand(0, 99999999)));
                $user->setInstance($instance);
                $manager->persist($user);

                $order = new Document\Order();
                $order->setOwner($user);
                $order->setType(1);
                $order->setInstance($instance);

                $material = new Document\JournalType();
                $material->setAuthors($names[rand(0, 99999999) % 10] . $surnames[rand(0, 99999999) % 10]);
                $material->setStartPage(rand(0, 9999999));
                $material->setEndPage(rand(0, 9999999));
                $material->setTitle(str_replace('. ', '', $generator->getContent(rand(1, 5), 'plain', false)));
                $material->setYear(rand(1980, 2012));

                $order->setMaterialData($material);

                $manager->persist($order);
                unset($material, $order);

                $order = new Document\Order();
                $order->setOwner($user);
                $order->setType(1);
                $order->setInstance($instance);

                $material = new Document\BookType();
                $material->setAuthors($names[rand(0, 99999999) % 10] . $surnames[rand(0, 99999999) % 10]);
                $material->setStartPage(rand(0, 9999999));
                $material->setEndPage(rand(0, 9999999));
                $material->setTitle(str_replace('. ', '', $generator->getContent(rand(1, 5), 'plain', false)));
                $material->setYear(rand(1980, 2012));

                $order->setMaterialData($material);

                $manager->persist($order);
                unset($material, $order);
                
                $order = new Document\Order();
                $order->setOwner($user);
                $order->setType(1);
                $order->setInstance($instance);

                $material = new Document\PatentType();
                $material->setAuthors($names[rand(0, 99999999) % 10] . $surnames[rand(0, 99999999) % 10]);
                $material->setStartPage(rand(0, 9999999));
                $material->setEndPage(rand(0, 9999999));
                $material->setTitle(str_replace('. ', '', $generator->getContent(rand(1, 5), 'plain', false)));
                $material->setYear(rand(1980, 2012));

                $order->setMaterialData($material);

                $manager->persist($order);
                unset($material, $order);
                
                $order = new Document\Order();
                $order->setOwner($user);
                $order->setType(1);
                $order->setInstance($instance);

                $material = new Document\CongressType();
                $material->setAuthors($names[rand(0, 99999999) % 10] . $surnames[rand(0, 99999999) % 10]);
                $material->setStartPage(rand(0, 9999999));
                $material->setEndPage(rand(0, 9999999));
                $material->setTitle(str_replace('. ', '', $generator->getContent(rand(1, 5), 'plain', false)));
                $material->setYear(rand(1980, 2012));

                $order->setMaterialData($material);

                $manager->persist($order);
                unset($material, $order);
                
                $order = new Document\Order();
                $order->setOwner($user);
                $order->setType(1);
                $order->setInstance($instance);

                $material = new Document\ThesisType();
                $material->setAuthors($names[rand(0, 99999999) % 10] . $surnames[rand(0, 99999999) % 10]);
                $material->setStartPage(rand(0, 9999999));
                $material->setEndPage(rand(0, 9999999));
                $material->setTitle(str_replace('. ', '', $generator->getContent(rand(1, 5), 'plain', false)));
                $material->setYear(rand(1980, 2012));

                $order->setMaterialData($material);

                $manager->persist($order);
                unset($material, $order);

                unset($user);
            }

            for ($j = 0; $j < 20; $j++)
            {
                $news = new Document\News();
                $news->setDate(date('Y-m-d H:i:s', mt_rand($min, $max)));
                $news->setTitle(str_replace('. ', '', $generator->getContent(rand(1, 5), 'plain', false)));
                $news->setText($generator->getContent(rand(100, 500), 'html', false));
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