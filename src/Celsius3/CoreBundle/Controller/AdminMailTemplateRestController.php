<?php

/*
 * Celsius3 - Order management
 * Copyright (C) 2014 PrEBi <info@prebi.unlp.edu.ar>
 *
 * This file is part of Celsius3.
 *
 * Celsius3 is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Celsius3 is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Celsius3.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Celsius3\CoreBundle\Controller;

use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\Controller\Annotations\Get;

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
        $em = $this->getDoctrine()->getManager();

        $templates = $em->getRepository('Celsius3CoreBundle:MailTemplate')
                ->findGlobalAndForInstance($this->getInstance(), $this->getDirectory())
                ->getQuery()
                ->getResult();

        $view = $this->view(array_values($templates), 200)->setFormat('json');

        return $this->handleView($view);
    }

    /**
     * GET Route annotation.
     * @Get("/{id}", name="admin_rest_contact_get", options={"expose"=true})
     */
    public function getContactAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $contact = $em->getRepository('Celsius3CoreBundle:Contact')->find($id);

        if (!$contact) {
            return $this->createNotFoundException('Contact not found.');
        }

        $view = $this->view($contact, 200)->setFormat('json');

        return $this->handleView($view);
    }

    /**
     * GET Route annotation.
     * @Get("/compiled/{code}/{request_id}", name="admin_rest_template_compiled_get", options={"expose"=true})
     */
    public function getCompiledTemplateAction($code, $request_id)
    {
        $em = $this->getDoctrine()->getManager();

        $request = $em->getRepository('Celsius3CoreBundle:Request')
            ->find($request_id);

        if (!$request) {
            return $this->createNotFoundException('Request not found.');
        }

        $template = $em->getRepository('Celsius3CoreBundle:MailTemplate')
            ->findGlobalAndForInstance($this->getInstance(), $this->getDirectory(), $code)
            ->getQuery()
            ->getSingleResult();

        if (!$template) {
            return $this->createNotFoundException('Template not found.');
        }

        $render = $this->get('celsius3_core.mail_manager')
            ->renderTemplate($code, $this->getInstance(), $request->getOwner(), $request->getOrder());

        $template->setText($render);

        $view = $this->view(array($template), 200)->setFormat('json');

        return $this->handleView($view);
    }
}
