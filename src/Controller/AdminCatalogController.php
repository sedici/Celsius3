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

use Celsius3\Entity\CatalogPosition;
use Celsius3\Form\Type\Filter\CatalogFilterType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;


/**
 * Catalog controller.
 *
 * @Route("/admin/catalog")
 */
class AdminCatalogController extends CatalogController
{

    /**
     * Lists all Catalog entities.
     *
     * @Route("/", name="admin_catalog")
     */
    public function index(): Response
    { return $this->baseInstanceIndex(CatalogFilterType::class); }

    /**
     * Displays a form to create a new Catalog entity.
     *
     * @Route("/new", name="admin_catalog_new")
     */
    public function new(): Response
    { return $this->baseInstanceNew(); }

    /**
     * Creates a new Catalog entity.
     *
     * @Route("/create", name="admin_catalog_create", methods={"POST"})
     */
    public function create(): RedirectResponse|Response
    { return $this->baseInstanceCreate(route: 'admin_catalog'); }


    /**
     * Displays a form to edit an existing Catalog entity.
     *
     * @Route("/{id}/edit", name="admin_catalog_edit")
     *
     * @param string $id The entity ID
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function edit($id): Response
    { return $this->baseInstanceEdit($id); }

    /**
     * Edits an existing Catalog entity.
     *
     * @Route("/{id}/update", name="admin_catalog_update", methods={"POST"})
     *
     * @param string $id The entity ID
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function update($id): RedirectResponse|Response
    { return $this->baseInstanceUpdate($id, 'admin_catalog'); }


    /**
     * Disables an existing Catalog entity.
     *
     * @Route("/{id}/disable", name="admin_catalog_disable", methods={"POST"})
     *
     * @param string $id The entity ID
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function disable(string $id): Response
    {
        $catalog = $this->findQuery($id);

        $catalog->setEnabled(false);

        $this->persistEntity($catalog);

        return $this->redirectToRoute('admin_catalog');
    }


    /**
     * Updates the position of a group of existing Catalog entities.
     *
     * @Route("/updateposition", name="admin_catalog_updateposition", methods={"POST"})
     *
     * @param string $id The entity ID
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function update_positions(): Response
    {
        $request = $this->requestStack->getCurrentRequest();
        $ids = (array) $request->request->get('ids');

        if ($ids) {
            foreach ($ids as $key => $id) {
                $position = $this->findQuery($id);

                if (!$position) {
                    $position = new CatalogPosition();
                    $position->setEnabled(true);
                    $position->setCatalog($this->findQuery($id));
                    $position->setInstance($this->instance);
                }

                $position->setPosition($key);

                $this->entityManager->persist($position);
            }
            $this->entityManager->flush();
        }

        return new Response(
            json_encode(['success' => 'Success'])
        );
    }
}