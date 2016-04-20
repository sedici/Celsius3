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
use FOS\RestBundle\Controller\Annotations\Get;
use JMS\Serializer\SerializationContext;
use Celsius3\CoreBundle\Entity\JournalType;
use Celsius3\CoreBundle\Exception\Exception;

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
        $context = SerializationContext::create()->setGroups(array('administration_order_show'));

        $em = $this->getDoctrine()->getManager();

        $catalogs = $em->getRepository('Celsius3CoreBundle:Catalog')
                ->findForInstanceAndGlobal($this->getInstance(), $this->getDirectory())
                ->getQuery()
                ->execute();

        $view = $this->view(array_values($catalogs), 200)->setFormat('json');
        $view->setSerializationContext($context);

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
            throw Exception::create(Exception::ENTITY_NOT_FOUND, 'exception.entity_not_found.catalog');
        }

        $view = $this->view($catalog, 200)->setFormat('json');

        return $this->handleView($view);
    }

    /**
     * GET Route annotation.
     * @Get("/results/{order_id}", name="admin_rest_catalog_results_order", options={"expose"=true})
     */
    public function getOrderCatalogResultsAction($order_id)
    {
        $context = SerializationContext::create()->setGroups(array('administration_order_show'));

        $em = $this->getDoctrine()->getManager();

        $order = $em->getRepository('Celsius3CoreBundle:Order')
                ->find($order_id);

        if (!$order) {
            throw Exception::create(Exception::ENTITY_NOT_FOUND, 'exception.entity_not_found.order');
        }

        if ($order->getMaterialData() instanceof JournalType) {
            if ($order->getMaterialData()->getJournal()) {
                $title = $order->getMaterialData()->getJournal()->getName();
            } else {
                $title = $order->getMaterialData()->getOther();
            }
        } else {
            $title = $order->getMaterialData()->getTitle();
        }

        $catalogs = $em->getRepository('Celsius3CoreBundle:Catalog')
                ->findForInstanceAndGlobal($this->getInstance(), $this->getDirectory())
                ->select('c.id')
                ->getQuery()
                ->execute();

        $response = array(
            'results' => $em->getRepository('Celsius3CoreBundle:Catalog')
                    ->getCatalogResults($catalogs, $title),
            'searches' => $em->getRepository('Celsius3CoreBundle:Event\\Event')
                    ->findSimilarSearches($order, $this->getInstance()),
        );

        $view = $this->view($response, 200)->setFormat('json');
        $view->setSerializationContext($context);

        return $this->handleView($view);
    }

}
