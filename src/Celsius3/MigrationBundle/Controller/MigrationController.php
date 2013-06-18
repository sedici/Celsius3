<?php

namespace Celsius3\MigrationBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Celsius\MigrationBundle\Document\Country;
use Celsius\MigrationBundle\Document\City;
use Celsius\MigrationBundle\Document\Institution;

/**
 * @Route("/migration")
 */
class MigrationController extends Controller
{
    /**
     * Returns the DocumentManager
     *
     * @return DocumentManager
     */
    protected function getDocumentManager()
    {
        return $this->get('doctrine.odm.mongodb.document_manager');
    }

    /**
     * @Route("/", name="migration_index")
     * @Template()
     */
    public function indexAction()
    {
        return array();
    }

    /**
     * @Route("/process", name="migration_process")
     * @Template()
     */
    public function processAction()
    {
        $host = $this->getRequest()->request->get('host');
        $username = $this->getRequest()->request->get('username');
        $password = $this->getRequest()->request->get('password');
        $database = $this->getRequest()->request->get('database');
        $port = $this->getRequest()->request->has('port') ? $this->getRequest()
                        ->request->has('port') : null;

        $this->get('celsius3_migration.migration_manager')
                ->migrate($host, $username, $password, $database, $port);

        return array(
                'countries' => $this->getDocumentManager()
                        ->getRepository('Celsius3CoreBundle:Country')
                        ->findAll(),);
    }
}
