<?php

namespace Celsius3\ApiBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;

class BaseController extends FOSRestController
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

    public function getInstance()
    {
        $instance = $this->getDocumentManager()
                ->getRepository('Celsius3CoreBundle:Instance')
                ->find($this->getRequest()->request->get('instance_id'));

        if (!$instance) {
            return $this->createNotFoundException('Instance not found');
        }

        return $instance;
    }
}
