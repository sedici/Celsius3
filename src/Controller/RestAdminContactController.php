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

use Celsius3\Controller\Base\ContactController;
use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\Controller\Annotations\Get;
use Symfony\Component\HttpFoundation\Response;

/**
 * User controller.
 * @Route("/rest/v1/admin/contact")
 */
class RestAdminContactController extends ContactController // BaseInstanceDependentRestController
{

    public function initialize(): void
    {
        parent::initialize();
        $this->setInstanceDependent(false);
    }


    // CONSULTAR

    // /**
    //  * @Get("/byInstitution/{id}", name="admin_rest_contact", options={"expose"=true})
    //  */
    // public function getContacts(string $id): Response
    // {
    //     $contacts = $this->repository->findBy([ 'institution' => $id ]);
    //     return $this->restRenderer->render(
    //         $contacts, serializerGroups: 'administration'
    //     );
    //     // $em = $this->getDoctrine()->getManager();

    //     // $contacts = $em->getRepository(Contact::class)
    //     //         ->findBy(array(
    //     //     'institution' => $institution_id,
    //     // ));

    //     // $view = $this->view(array_values($contacts), 200)->setFormat('json');

    //     // return $this->handleView($view);
    // }


    /**
     * @Get("/{id}/show", name="rest_admin_contact", options={"expose"=true})
     */
    public function getContact(string $id): Response
    { return $this->restRenderer->show($id, 'administration'); }
}
