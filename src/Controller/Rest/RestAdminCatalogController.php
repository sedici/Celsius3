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

declare(strict_types=1);

namespace Celsius3\Controller\Rest;

use Celsius3\Controller\Base\CatalogController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;


#[
    Route('/rest/v1/admin/catalogs'),
    IsGranted('ROLE_ADMIN')
]
final class RestAdminCatalogController extends CatalogController
{    
    
    #[Route("/", name: "admin_rest_catalog", options: ['expose' => true])]
    public function restIndex(): Response
    { return $this->restRenderer->index('administration_order_show'); }


    #[Route("/{id}", name: "admin_rest_catalog_get", options: ['expose' => true])]
    public function restShow(string $id): Response
    { return $this->restRenderer->show($id, 'administration_order_show'); }


    #[Route("/results/{order_id}", name: "admin_rest_catalog_results_order", options: ['expose' => true])]
    public function catalogResultsOrder(string $order_id): Response
    { return $this->restRenderer->render(
        $this->orderCatalogResults($order_id),
        serializerGroups: 'administration_order_show'
    ); }
}