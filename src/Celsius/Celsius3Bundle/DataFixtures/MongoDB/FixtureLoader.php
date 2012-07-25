<?php

namespace Celsius\Celsius3Bundle\DataFixtures\MongoDB;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Celsius\Celsius3Bundle\Document;

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

        for ($i = 0; $i < 5; $i++)
        {
            $instance = new Document\Instance();
            $instance->setName(md5(rand(0, 99999999)));
            $instance->setAbbreviation(md5(rand(0, 99999999)));
            $instance->setTitle(md5(rand(0, 99999999)));
            $instance->setWebsite(md5(rand(0, 99999999)) . '.test.com');
            $instance->setEmail(md5(rand(0, 99999999)) . '@test.com');
            $instance->setUrl($i);
            $manager->persist($instance);
            $manager->flush();

            for ($j = 0; $j < 50; $j++)
            {
                $user = new Document\BaseUser();
                $user->setName($names[rand(0, 99999999) % 10]);
                $user->setSurname($surnames[rand(0, 99999999) % 10]);
                $user->setBirthdate(date('Y-m-d'));
                $user->setUsername(md5(rand(0, 99999999)));
                $user->setPassword(md5(rand(0, 99999999)));
                $user->setEmail($user->getName() . $user->getSurname() . md5(rand(0, 99999999)) . $i . $j . '@test.com');
                $user->setAddress('address' . md5(rand(0, 99999999)));
                $user->setInstance($instance);
                $manager->persist($user);

                for ($k = 0; $k < 5; $k++)
                {
                    $order = new Document\Order();
                    $order->setOwner($user);
                    $order->setType(1);
                    $order->setInstance($instance);

                    $material = new Document\JournalType();
                    $material->setAuthors($names[rand(0, 99999999) % 10] . $surnames[rand(0, 99999999) % 10]);
                    $material->setStartPage(rand(0, 9999999));
                    $material->setEndPage(rand(0, 9999999));
                    $material->setTitle('title' . md5(rand(0, 9999999)));
                    $material->setYear(rand(0, 9999999));

                    $order->setMaterialData($material);

                    $manager->persist($order);
                    unset($material, $order);
                }

                for ($k = 0; $k < 5; $k++)
                {
                    $order = new Document\Order();
                    $order->setOwner($user);
                    $order->setType(1);
                    $order->setInstance($instance);

                    $material = new Document\BookType();
                    $material->setAuthors($names[rand(0, 99999999) % 10] . $surnames[rand(0, 99999999) % 10]);
                    $material->setStartPage(rand(0, 9999999));
                    $material->setEndPage(rand(0, 9999999));
                    $material->setTitle('title' . md5(rand(0, 9999999)));
                    $material->setYear(rand(0, 9999999));

                    $order->setMaterialData($material);

                    $manager->persist($order);
                    unset($material, $order);
                }

                unset($user);
            }

            for ($j = 0; $j < 50; $j++)
            {
                $user = new Document\Librarian();
                $user->setName($names[rand(0, 99999999) % 10]);
                $user->setSurname($surnames[rand(0, 99999999) % 10]);
                $user->setBirthdate(date('Y-m-d'));
                $user->setUsername(md5(rand(0, 99999999)));
                $user->setPassword(md5(rand(0, 99999999)));
                $user->setEmail($user->getName() . $user->getSurname() . md5(rand(0, 99999999)) . $i . $j . '@test.com');
                $user->setAddress('address' . md5(rand(0, 99999999)));
                $user->setInstance($instance);
                $manager->persist($user);

                for ($k = 0; $k < 5; $k++)
                {
                    $order = new Document\Order();
                    $order->setOwner($user);
                    $order->setType(1);
                    $order->setInstance($instance);

                    $material = new Document\JournalType();
                    $material->setAuthors($names[rand(0, 99999999) % 10] . $surnames[rand(0, 99999999) % 10]);
                    $material->setStartPage(rand(0, 9999999));
                    $material->setEndPage(rand(0, 9999999));
                    $material->setTitle('title' . md5(rand(0, 9999999)));
                    $material->setYear(rand(0, 9999999));

                    $order->setMaterialData($material);

                    $manager->persist($order);
                    unset($material, $order);
                }

                for ($k = 0; $k < 5; $k++)
                {
                    $order = new Document\Order();
                    $order->setOwner($user);
                    $order->setType(1);
                    $order->setInstance($instance);

                    $material = new Document\BookType();
                    $material->setAuthors($names[rand(0, 99999999) % 10] . $surnames[rand(0, 99999999) % 10]);
                    $material->setStartPage(rand(0, 9999999));
                    $material->setEndPage(rand(0, 9999999));
                    $material->setTitle('title' . md5(rand(0, 9999999)));
                    $material->setYear(rand(0, 9999999));

                    $order->setMaterialData($material);

                    $manager->persist($order);
                    unset($material, $order);
                }

                unset($user);
            }

            for ($j = 0; $j < 50; $j++)
            {
                $user = new Document\Admin();
                $user->setName($names[rand(0, 99999999) % 10]);
                $user->setSurname($surnames[rand(0, 99999999) % 10]);
                $user->setBirthdate(date('Y-m-d'));
                $user->setUsername(md5(rand(0, 99999999)));
                $user->setPassword(md5(rand(0, 99999999)));
                $user->setEmail($user->getName() . $user->getSurname() . md5(rand(0, 99999999)) . $i . $j . '@test.com');
                $user->setAddress('address' . md5(rand(0, 99999999)));
                $user->setInstance($instance);
                $manager->persist($user);

                for ($k = 0; $k < 5; $k++)
                {
                    $order = new Document\Order();
                    $order->setOwner($user);
                    $order->setType(1);
                    $order->setInstance($instance);

                    $material = new Document\JournalType();
                    $material->setAuthors($names[rand(0, 99999999) % 10] . $surnames[rand(0, 99999999) % 10]);
                    $material->setStartPage(rand(0, 9999999));
                    $material->setEndPage(rand(0, 9999999));
                    $material->setTitle('title' . md5(rand(0, 9999999)));
                    $material->setYear(rand(0, 9999999));

                    $order->setMaterialData($material);

                    $manager->persist($order);
                    unset($material, $order);
                }

                for ($k = 0; $k < 5; $k++)
                {
                    $order = new Document\Order();
                    $order->setOwner($user);
                    $order->setType(1);
                    $order->setInstance($instance);

                    $material = new Document\BookType();
                    $material->setAuthors($names[rand(0, 99999999) % 10] . $surnames[rand(0, 99999999) % 10]);
                    $material->setStartPage(rand(0, 9999999));
                    $material->setEndPage(rand(0, 9999999));
                    $material->setTitle('title' . md5(rand(0, 9999999)));
                    $material->setYear(rand(0, 9999999));

                    $order->setMaterialData($material);

                    $manager->persist($order);
                    unset($material, $order);
                }

                unset($user);
            }

            for ($j = 0; $j < 50; $j++)
            {
                $user = new Document\Superadmin();
                $user->setName($names[rand(0, 99999999) % 10]);
                $user->setSurname($surnames[rand(0, 99999999) % 10]);
                $user->setBirthdate(date('Y-m-d'));
                $user->setUsername(md5(rand(0, 99999999)));
                $user->setPassword(md5(rand(0, 99999999)));
                $user->setEmail($user->getName() . $user->getSurname() . md5(rand(0, 99999999)) . $i . $j . '@test.com');
                $user->setAddress('address' . md5(rand(0, 99999999)));
                $user->setInstance($instance);
                $manager->persist($user);

                for ($k = 0; $k < 5; $k++)
                {
                    $order = new Document\Order();
                    $order->setOwner($user);
                    $order->setType(1);
                    $order->setInstance($instance);

                    $material = new Document\JournalType();
                    $material->setAuthors($names[rand(0, 99999999) % 10] . $surnames[rand(0, 99999999) % 10]);
                    $material->setStartPage(rand(0, 9999999));
                    $material->setEndPage(rand(0, 9999999));
                    $material->setTitle('title' . md5(rand(0, 9999999)));
                    $material->setYear(rand(0, 9999999));

                    $order->setMaterialData($material);

                    $manager->persist($order);
                    unset($material, $order);
                }

                for ($k = 0; $k < 5; $k++)
                {
                    $order = new Document\Order();
                    $order->setOwner($user);
                    $order->setType(1);
                    $order->setInstance($instance);

                    $material = new Document\BookType();
                    $material->setAuthors($names[rand(0, 99999999) % 10] . $surnames[rand(0, 99999999) % 10]);
                    $material->setStartPage(rand(0, 9999999));
                    $material->setEndPage(rand(0, 9999999));
                    $material->setTitle('title' . md5(rand(0, 9999999)));
                    $material->setYear(rand(0, 9999999));

                    $order->setMaterialData($material);

                    $manager->persist($order);
                    unset($material, $order);
                }

                unset($user);
            }

            for ($j = 0; $j < 100; $j++)
            {
                $news = new Document\News();
                $news->setDate(date('Y-m-d H:i:s'));
                $news->setTitle(md5(rand(0, 9999999)));
                $news->setText(md5(rand(0, 9999999)));
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