<?php

/*
 * Celsius3 - Order management
 * Copyright (C) 2014 PREBI-SEDICI <info@prebi.unlp.edu.ar> http://prebi.unlp.edu.ar http://sedici.unlp.edu.ar
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

namespace Celsius3\Controller;

use Celsius3\Entity\Contact;
use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\Controller\Annotations\Get;
use Celsius3\Exception\Exception;

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
    public function getContacts($institution_id)
    {
        $em = $this->getDoctrine()->getManager();

        $contacts = $em->getRepository(Contact::class)
                ->findBy(array(
            'institution' => $institution_id,
        ));

        $view = $this->view(array_values($contacts), 200)->setFormat('json');

        return $this->handleView($view);
    }

    /**
     * GET Route annotation.
     * @Get("/{id}", name="admin_rest_contact_get", options={"expose"=true})
     */
    public function getContact($id)
    {
        $em = $this->getDoctrine()->getManager();

        $contact = $em->getRepository(Contact::class)->find($id);

        if (!$contact) {
            throw Exception::create(Exception::ENTITY_NOT_FOUND, 'exception.entity_not_found.contact');
        }

        $view = $this->view($contact, 200)->setFormat('json');

        return $this->handleView($view);
    }

}
