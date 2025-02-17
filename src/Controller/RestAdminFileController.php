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

use Celsius3\Controller\Base\FileController;
use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use Symfony\Component\HttpFoundation\Response;

/**
 * User controller.
 * @Route("/rest/v1/admin/file")
 */
class RestAdminFileController extends FileController // BaseInstanceDependentRestController
{

    public function initialize(): void
    {
        parent::initialize();
        $this->setInstanceDependent(false);
    }


    // /**
    //  * @Get("/{id}/show", name="rest_admin_file", options={"expose"=true})
    //  */
    // public function getContact(string $id): Response
    // { return $this->restRenderer->show($id, 'administration_order_show'); }


    /**
     * @Post("/{id}/state", name="admin_rest_file_state", options={"expose"=true})
     */
    public function changeState(string $id): Response
    {
        $file = $this->repository->find($id);
        $file->setEnabled(!$file->getEnabled());
        $this->persistEntity($file);
        return $this->restRenderer->render(
            $file, serializerGroups: 'administration_order_show'
        );

        // $em = $this->getDoctrine()->getManager();

        // $file = $em->getRepository(File::class)->find($file_id);

        // if (!$file) {
        //     throw Exception::create(Exception::ENTITY_NOT_FOUND, 'exception.entity_not_found.file');
        // }

        // $file->setEnabled(!$file->getEnabled());

        // $em->persist($file);
        // $em->flush();

        // $view = $this->view($file, 200)->setFormat('json');

        // $context = new Context();
        // $context->addGroup('administration_order_show');
        // $view->setContext($context);

        // return $this->handleView($view);
    }

}
