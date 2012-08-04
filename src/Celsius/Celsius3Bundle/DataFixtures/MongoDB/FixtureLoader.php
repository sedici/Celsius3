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
                $news->setText('<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed lacus leo, mattis vitae ullamcorper pulvinar, euismod nec neque. Nulla ultricies, metus eget molestie accumsan, eros orci feugiat orci, non facilisis nisi eros id lectus. Phasellus tempus scelerisque suscipit. Pellentesque quis dolor urna. Proin commodo lectus a erat porttitor tincidunt sit amet at leo. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. In hac habitasse platea dictumst.</p>
<p>Aliquam sagittis purus eget odio molestie a laoreet nulla pretium. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Vivamus consectetur sodales velit, ac cursus diam iaculis vitae. Sed porta quam sed nibh tincidunt aliquam. Ut sit amet dignissim arcu. Integer euismod nulla ac enim mollis eleifend a sed mi. Vivamus porta adipiscing magna vel rhoncus. Aenean viverra, diam eu venenatis semper, nunc mi varius felis, in feugiat enim ipsum ut nulla.</p>
<p>Nunc tempus venenatis est, ac placerat odio pellentesque id. In nec sapien diam. Mauris lobortis, enim ac accumsan scelerisque, risus libero pulvinar dui, vitae pulvinar erat dolor in felis. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Aliquam erat volutpat. Sed cursus lectus ut elit adipiscing eu scelerisque erat iaculis. Aliquam non leo vitae mauris vestibulum consequat ut ut nunc. Suspendisse potenti. Nulla id egestas nisl. Duis quam lacus, tincidunt ac pretium at, auctor nec leo. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Fusce ullamcorper mauris id neque blandit pellentesque. Mauris ultricies congue nulla vitae lobortis. Vestibulum tempor, libero non eleifend auctor, magna velit adipiscing neque, sit amet sodales augue lacus ullamcorper sem. Aliquam at ullamcorper massa.</p>');
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