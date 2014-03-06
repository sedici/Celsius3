<?php

namespace Celsius3\CoreBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;

class BaseRestController extends FOSRestController
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
    
    protected function getDirectory()
    {
        return $this->get('celsius3_core.instance_manager')->getDirectory();
    }

}
