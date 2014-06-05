<?php

namespace Celsius3\CoreBundle\Controller;

use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;

/**
 * User controller.
 *
 * @Route("/admin/rest/mail_template")
 */
class AdminMailTemplateRestController extends BaseInstanceDependentRestController
{

    /**
     * GET Route annotation.
     * @Get("/", name="admin_rest_mail_template", options={"expose"=true})
     */
    public function getMailTemplatesAction()
    {
        $dm = $this->getDocumentManager();

        $templates = $dm->getRepository('Celsius3CoreBundle:MailTemplate')
                ->findGlobalAndForInstance($this->getInstance(), $this->getDirectory())
                ->getQuery()
                ->execute()
                ->toArray();

        $view = $this->view(array_values($templates), 200)
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
