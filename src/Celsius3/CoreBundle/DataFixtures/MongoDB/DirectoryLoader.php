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
use Celsius3\CoreBundle\Manager\StateManager;
use Celsius3\CoreBundle\Manager\InstanceManager;

/**
 * Description of FixtureLoader
 *
 * @author agustin
 */
class DirectoryLoader extends AbstractFixture implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface
{

    private $container;
    private $directory_configurations = array(
        'instance_title' => array(
            'name' => 'Title',
            'value' => 'Celsius3 Directory',
            'type' => 'string',
        ),
        'results_per_page' => array(
            'name' => 'Results per page',
            'value' => '10',
            'type' => 'integer',
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
    );
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
        'mail_signature' => array(
            'name' => 'Mail signature',
            'value' => '',
            'type' => 'text',
        ),
        'api_key' => array(
            'name' => 'Api Key',
            'value' => '',
            'type' => 'string',
        ),
    );
    private $contact_types = array(
        'Director',
        'Librarian',
        'Technician',
    );
    private $state_types = array(
        StateManager::STATE__CREATED,
        StateManager::STATE__SEARCHED,
        StateManager::STATE__REQUESTED,
        StateManager::STATE__APPROVAL_PENDING,
        StateManager::STATE__RECEIVED,
        StateManager::STATE__DELIVERED,
        StateManager::STATE__CANCELLED,
        StateManager::STATE__ANNULLED,
        StateManager::STATE__TAKEN,
    );

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        $generator = Factory::create('en');
        $generator->seed(1113);

        /*
         * Instancia que representa al directorio
         */
        $directory = new Document\Instance();
        $directory->setName('Directory');
        $directory->setAbbreviation('Directory');
        $directory->setWebsite('http://www.celsius3.com.localhost');
        $directory->setEmail('admin@celsius3.com');
        $directory->setUrl(InstanceManager::INSTANCE__DIRECTORY);
        $directory->setEnabled(false);
        $manager->persist($directory);
        $manager->flush();

        $this->addReference('directory', $directory);

        /*
         * Configuración del directorio
         */
        foreach ($this->directory_configurations as $key => $data) {
            $configuration = new Document\Configuration();
            $configuration->setName($data['name']);
            $configuration->setKey($key);
            $configuration->setValue($data['value']);
            $configuration->setType($data['type']);
            $configuration->setInstance($directory);

            if ($key == 'instance_description') {
                $configuration->setValue($generator->text(1000));
            }

            $manager->persist($configuration);
            unset($configuration);
        }
        $manager->flush();

        /*
         * Creacion de noticias del directorio
         */
        for ($j = 0; $j < $generator->randomNumber(10, 20); $j++) {
            $news = new Document\News();
            $news->setDate($generator->date('Y-m-d H:i:s'));
            $news->setTitle(str_replace('.', '', $generator->sentence));
            $news->setText($generator->text(2000));
            $news->setInstance($directory);
            $manager->persist($news);
            unset($news);
        }

        /*
         * Configuración modelo para las Instancias
         */
        foreach ($this->configurations as $key => $data) {
            $configuration = new Document\Configuration();
            $configuration->setName($data['name']);
            $configuration->setKey($key);
            $configuration->setValue($data['value']);
            $configuration->setType($data['type']);

            if ($key == 'instance_description') {
                $configuration->setValue($generator->text(1000));
            }

            $manager->persist($configuration);
            unset($configuration);
        }

        /*
         * Tipos de estado para los pedidos
         * Revisar si esto sigue siendo necesario o se puede reemplazar por la constante en el objeto
         */
        foreach ($this->state_types as $key => $value) {
            $state_type = new Document\StateType();
            $state_type->setName($value);
            $state_type->setPosition($key);
            $manager->persist($state_type);
            unset($state_type);
        }
        $manager->flush();

        /*
         * Listado global de revistas
         */
        for ($i = 0; $i < 100; $i++) {
            $journal = new Document\Journal();
            $journal->setName(str_replace('.', '', $generator->sentence));
            $journal->setAbbreviation(strtoupper($generator->word));
            $journal->setISSN($generator->randomNumber(8));
            $journal->setISSNE($generator->bothify('#######X'));
            $journal->setFrecuency($generator->randomElement(array('anual', 'semestral', 'mensual')));
            $journal->setResponsible($generator->name);
            $journal->setInstance($directory);
            $manager->persist($journal);
            unset($journal);
        }
        $manager->flush();

        foreach ($this->contact_types as $contacttype) {
            $ct = new Document\ContactType();
            $ct->setName($contacttype);
            $manager->persist($ct);
            unset($ct);
        }

        /*
         * Carga de catalogos globales
         */
        for ($i = 0; $i < 50; $i++) {
            $catalog = new Document\Catalog();
            $catalog->setName($generator->company);
            $catalog->setUrl($generator->url);
            $catalog->setInstance($directory);
            $manager->persist($catalog);
            unset($catalog);
        }
        $manager->flush();
    }

    public function getOrder()
    {
        return 1;
    }

}