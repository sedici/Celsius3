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
 * @Route("/admin/rest/contact")
 */
class AdminContactRestController extends BaseInstanceDependentRestController
{

    /**
     * GET Route annotation.
     * @Get("/{institution_id}", name="admin_rest_contact", options={"expose"=true})
     */
    public function getContactsAction($institution_id)
    {
        $em = $this->getDoctrine()->getManager();

        $contacts = $em->getRepository('Celsius3CoreBundle:Contact')
                ->findBy(array(
                    'institution_id' => $institution_id,
                ));

        $view = $this->view(array_values($contacts), 200)->setFormat('json');

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

}