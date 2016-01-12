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

namespace Celsius3\CoreBundle\Controller;

use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\Controller\Annotations\Post;
use JMS\Serializer\SerializationContext;

/**
 * User controller.
 *
 * @Route("/admin/rest/file")
 */
class AdminFileRestController extends BaseInstanceDependentRestController
{

    /**
     * @Post("/{file_id}/state", name="admin_rest_file_state", options={"expose"=true})
     */
    public function changeStateAction($file_id)
    {
        $context = SerializationContext::create()->setGroups(array('administration_order_show'));

        $em = $this->getDoctrine()->getManager();

        $file = $em->getRepository('Celsius3CoreBundle:File')->find($file_id);

        if (!$file) {
            throw $this->createNotFoundException('Unable to find File.');
        }

        $file->setEnabled(!$file->getEnabled());

        $em->persist($file);
        $em->flush();

        $view = $this->view($file, 200)->setFormat('json');
        $view->setSerializationContext($context);

        return $this->handleView($view);
    }
}
