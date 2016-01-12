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

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Celsius3\CoreBundle\Entity\Catalog;
use Celsius3\CoreBundle\Form\Type\CatalogType;
use Celsius3\CoreBundle\Filter\Type\CatalogFilterType;

/**
 * Location controller.
 *
 * @Route("/admin/catalog")
 */
class AdminCatalogController extends BaseInstanceDependentController
{

    protected function listQuery($name)
    {
        return $this->getDoctrine()->getManager()
                        ->getRepository('Celsius3CoreBundle:' . $name)
                        ->findForInstanceAndGlobal($this->getInstance(), $this->getDirectory());
    }

    /**
     * Lists all Catalog entities.
     *
     * @Route("/", name="admin_catalog")
     * @Template()
     *
     * @return array
     */
    public function indexAction(Request $request)
    {
        $filter_form = $this->createForm(CatalogFilterType::class, null, array(
            'instance' => $this->getInstance(),
        ));

        $filter_form->handleRequest($request);
        $query = $this->filter('Catalog', $filter_form, $this->listQuery('Catalog'));

        return array(
            'pagination' => $query->getQuery()->getResult(),
            'filter_form' => $filter_form->createView(),
            'directory' => $this->getDirectory(),
            'instance' => $this->getInstance()
        );
    }

    /**
     * Displays a form to create a new Catalog entity.
     *
     * @Route("/new", name="admin_catalog_new")
     * @Template()
     *
     * @return array
     */
    public function newAction()
    {
        return $this->baseNew('Catalog', new Catalog(), CatalogType::class, array(
            'instance' => $this->getInstance(),
        ));
    }

    /**
     * Creates a new Catalog entity.
     *
     * @Route("/create", name="admin_catalog_create")
     * @Method("post")
     * @Template("Celsius3CoreBundle:AdminCatalog:new.html.twig")
     *
     * @return array
     */
    public function createAction()
    {
        return $this->baseCreate('Catalog', new Catalog(), CatalogType::class, array(
            'instance' => $this->getInstance(),
        ), 'admin_catalog');
    }

    /**
     * Displays a form to edit an existing Catalog entity.
     *
     * @Route("/{id}/edit", name="admin_catalog_edit")
     * @Template()
     * @param string $id The entity ID
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function editAction($id)
    {
        return $this->baseEdit('Catalog', $id, CatalogType::class, array(
            'instance' => $this->getInstance(),
        ));
    }

    /**
     * Edits an existing Catalog entity.
     *
     * @Route("/{id}/update", name="admin_catalog_update")
     * @Method("post")
     * @Template("Celsius3CoreBundle:AdminCatalog:edit.html.twig")
     *
     * @param string $id The entity ID
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function updateAction($id)
    {
        return $this->baseUpdate('Catalog', $id, CatalogType::class, array(
            'instance' => $this->getInstance(),
        ), 'admin_catalog');
    }

    /**
     * Updates de order of each Catalog
     *
     * @Route("/persist", name="admin_catalog_persist", options={"expose"=true})
     * @Method("post")
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function persistAction(Request $request)
    {
        $ids = $request->request->get('ids');
        $em = $this->getDoctrine()->getManager();

        if ($ids) {
            foreach ($ids as $key => $id) {
                $position = $em->getRepository('Celsius3CoreBundle:CatalogPosition')
                        ->findOneBy(array(
                    'catalog' => $id,
                    'instance' => $this->getInstance()->getId(),
                ));
                if ($position) {
                    $position->setPosition($key);
                    $em->persist($position);
                }
            }
            $em->flush();
        }

        return new Response(json_encode(array(
            'success' => 'Success',
        )));
    }

}
