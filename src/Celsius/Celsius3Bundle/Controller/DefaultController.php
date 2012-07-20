<?php

namespace Celsius\Celsius3Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Celsius\Celsius3Bundle\Document;

/**
 * @Route("/public") 
 */
class DefaultController extends Controller
{

    /**
     * @Route("/load", name="load")
     * @Template()
     */
    public function loadAction()
    {
        set_time_limit(0);
        ini_set('memory_limit', '-1');

        $names = array('John', 'Tom', 'Laura', 'Mike', 'Chris', 'Michelle', 'Liz', 'Mary', 'Joe', 'Kevin');
        $surnames = array('Perez', 'Rodriguez', 'Dominguez', 'Martinez', 'Gonzalez', 'Ibañez', 'Fernandez', 'Ledesma', 'Acuña', 'Estevez');

        $dm = $this->get('doctrine.odm.mongodb.document_manager');

        for ($i = 0; $i < 10; $i++)
        {
            $instance = new Document\Instance();
            $instance->setName(md5(rand(0, 99999999)));
            $instance->setAbbreviation(md5(rand(0, 99999999)));
            $instance->setTitle(md5(rand(0, 99999999)));
            $instance->setWebsite(md5(rand(0, 99999999)) . '.test.com');
            $instance->setEmail(md5(rand(0, 99999999)) . '@test.com');
            $instance->setUrl($i);
            $dm->persist($instance);
            $dm->flush();

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
                $dm->persist($user);

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

                    $dm->persist($order);
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

                    $dm->persist($order);
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
                $dm->persist($user);

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

                    $dm->persist($order);
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

                    $dm->persist($order);
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
                $dm->persist($user);

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

                    $dm->persist($order);
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

                    $dm->persist($order);
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
                $dm->persist($user);

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

                    $dm->persist($order);
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

                    $dm->persist($order);
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
                $dm->persist($news);
                unset($news);
            }

            $dm->flush();
            $dm->clear();
            unset($instance);
        }

        return array('name' => "");
    }

    /**
     * @Route("/", name="index")
     * @Template()
     */
    public function indexAction()
    {
        return array();
    }

}
