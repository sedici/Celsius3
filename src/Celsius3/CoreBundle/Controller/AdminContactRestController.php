<?php

namespace Celsius3\CoreBundle\Controller;

use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;

/**
 * User controller.
 *
 * @Route("/admin/rest/institution")
 */
class AdminContactRestController extends BaseInstanceDependentRestController
{

    /**
     * GET Route annotation.
     * @Get("/{institution_id}", name="admin_rest_contact", options={"expose"=true})
     */
    public function getContactsAction($institution_id)
    {
        $dm = $this->getDocumentManager();

        $contacts = $dm->getRepository('Celsius3CoreBundle:Contact')
                ->findBy(array(
            'institution.id' => $institution_id,
        ));

        $view = $this->view(array_values($contacts), 200)
                ->setFormat('json');

        return $this->handleView($view);
    }

    /**
     * GET Route annotation.
     * @Get("/{id}", name="admin_rest_contact_get", options={"expose"=true})
     */
    public function getContactAction($id)
    {
        $dm = $this->getDocumentManager();

        $contact = $dm->getRepository('Celsius3CoreBundle:Contact')
                ->find($id);

        if (!$contact) {
            return $this->createNotFoundException('Contact not found.');
        }

        $view = $this->view($contact, 200)
                ->setFormat('json');

        return $this->handleView($view);
    }

}
