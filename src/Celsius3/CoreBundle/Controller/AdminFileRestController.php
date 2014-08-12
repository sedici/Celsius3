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
use FOS\RestBundle\Controller\Annotations\Post;

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
        $dm = $this->getDocumentManager();

        $file = $dm->getRepository('Celsius3CoreBundle:File')
                ->find($file_id);

        if (!$file) {
            throw $this->createNotFoundException('Unable to find File.');
        }

        $file->setEnabled(!$file->getEnabled());

        $dm->persist($file);
        $dm->flush();

        $view = $this->view($file, 200)
                ->setFormat('json');

        return $this->handleView($view);
    }

}