<?php

namespace Celsius\Celsius3Bundle\DataFixtures\MongoDB;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Celsius\Celsius3Bundle\Document;
use Celsius\Celsius3Bundle\Helper\LoremIpsumHelper;

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

        for ($i = 0; $i < 5; $i++)
        {
            $instance = new Document\Instance();
            $instance->setName(str_replace('. ', '', $generator->getContent(rand(1, 5), 'plain', false)));
            $instance->setAbbreviation(str_replace('. ', '', $generator->getContent(1, 'plain', false)));
            $instance->setTitle(str_replace('. ', '', $generator->getContent(rand(1, 5), 'plain', false)));
            $instance->setWebsite(md5(rand(0, 99999999)) . '.test.com');
            $instance->setEmail(md5(rand(0, 99999999)) . '@test.com');
            $instance->setUrl(str_replace('. ', '', $i . '_' . $generator->getContent(1, 'plain', false)));
            $manager->persist($instance);
            $manager->flush();

            for ($j = 0; $j < 50; $j++)
            {
                $user = new Document\BaseUser();
                $user->setName($names[rand(0, 99999999) % 10]);
                $user->setSurname($surnames[rand(0, 99999999) % 10]);
                $user->setBirthdate(date('Y-m-d', mt_rand($min,$max)));
                $user->setUsername($user->getName() . '_' . md5(rand(0, 99999999)));
                $user->setPassword(md5(rand(0, 99999999)));
                $user->setEmail($user->getName() . $user->getSurname() . md5(rand(0, 99999999)) . $i . $j . '@test.com');
                $user->setAddress('address_' . md5(rand(0, 99999999)));
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
                    $material->setTitle(str_replace('. ', '', $generator->getContent(rand(1, 5), 'plain', false)));
                    $material->setYear(rand(1980, 2012));

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
                    $material->setTitle(str_replace('. ', '', $generator->getContent(rand(1, 5), 'plain', false)));
                    $material->setYear(rand(1980, 2012));

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
                $user->setBirthdate(date('Y-m-d', mt_rand($min,$max)));
                $user->setUsername($user->getName() . '_' . md5(rand(0, 99999999)));
                $user->setPassword(md5(rand(0, 99999999)));
                $user->setEmail($user->getName() . $user->getSurname() . md5(rand(0, 99999999)) . $i . $j . '@test.com');
                $user->setAddress('address_' . md5(rand(0, 99999999)));
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
                    $material->setTitle(str_replace('. ', '', $generator->getContent(rand(1, 5), 'plain', false)));
                    $material->setYear(rand(1980, 2012));

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
                    $material->setTitle(str_replace('. ', '', $generator->getContent(rand(1, 5), 'plain', false)));
                    $material->setYear(rand(1980, 2012));

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
                $user->setBirthdate(date('Y-m-d', mt_rand($min,$max)));
                $user->setUsername($user->getName() . '_' . md5(rand(0, 99999999)));
                $user->setPassword(md5(rand(0, 99999999)));
                $user->setEmail($user->getName() . $user->getSurname() . md5(rand(0, 99999999)) . $i . $j . '@test.com');
                $user->setAddress('address_' . md5(rand(0, 99999999)));
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
                    $material->setTitle(str_replace('. ', '', $generator->getContent(rand(1, 5), 'plain', false)));
                    $material->setYear(rand(1980, 2012));

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
                    $material->setTitle(str_replace('. ', '', $generator->getContent(rand(1, 5), 'plain', false)));
                    $material->setYear(rand(1980, 2012));

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
                $user->setBirthdate(date('Y-m-d', mt_rand($min,$max)));
                $user->setUsername($user->getName() . '_' . md5(rand(0, 99999999)));
                $user->setPassword(md5(rand(0, 99999999)));
                $user->setEmail($user->getName() . $user->getSurname() . md5(rand(0, 99999999)) . $i . $j . '@test.com');
                $user->setAddress('address_' . md5(rand(0, 99999999)));
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
                    $material->setTitle(str_replace('. ', '', $generator->getContent(rand(1, 5), 'plain', false)));
                    $material->setYear(rand(1980, 2012));

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
                    $material->setTitle(str_replace('. ', '', $generator->getContent(rand(1, 5), 'plain', false)));
                    $material->setYear(rand(1980, 2012));

                    $order->setMaterialData($material);

                    $manager->persist($order);
                    unset($material, $order);
                }

                unset($user);
            }

            for ($j = 0; $j < 100; $j++)
            {
                $news = new Document\News();
                $news->setDate(date('Y-m-d H:i:s', mt_rand($min,$max)));
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