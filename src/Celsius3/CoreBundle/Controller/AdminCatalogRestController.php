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
 * @Route("/admin/rest/catalogs")
 */
class AdminCatalogRestController extends BaseInstanceDependentRestController
{

    /**
     * GET Route annotation.
     * @Get("", name="admin_rest_catalog", options={"expose"=true})
     */
    public function getCatalogsAction()
    {
        $catalogs = $this->get('celsius3_core.catalog_manager')->getAllCatalogs($this->getInstance(), $this->getDirectory());

        $view = $this->view(array_values($catalogs), 200)->setFormat('json');

        return $this->handleView($view);
    }

    /**
     * GET Route annotation.
     * @Get("/{id}", name="admin_rest_catalog_get", options={"expose"=true})
     */
    public function getCatalogAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $catalog = $em->getRepository('Celsius3CoreBundle:Catalog')->find($id);

        if (!$catalog) {
            return $this->createNotFoundException('Catalog not found.');
        }

        $view = $this->view($catalog, 200)->setFormat('json');

        return $this->handleView($view);
    }

    /**
     * GET Route annotation.
     * @Get("/results/{title}", name="admin_rest_catalog_results", options={"expose"=true})
     */
    public function getCatalogResultsAction($title)
    {
        $catalogs = $this->get('celsius3_core.catalog_manager')->getAllCatalogs($this->getInstance(), $this->getDirectory());
        $results = $this->get('celsius3_core.catalog_manager')->getCatalogResults($catalogs, $title);

        $view = $this->view(array_values($results), 200)->setFormat('json');

        return $this->handleView($view);
    }

}
