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

namespace Celsius3\Controller\Html;

use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Celsius3\Entity\Instance;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Celsius3\Controller\Base\CityController;

/**
 * Location controller.
 *
 * @Route("/superadmin/city")
 */
class SuperadminCityController extends CityController
{

    // protected function getInstance(): Instance
    // { return $this->directory; }


    // /**
    //  * Lists all City entities.
    //  *
    //  * @Route("/", name="superadmin_city")
    //  */
    // public function index(PaginatorInterface $paginator): Response
    // { return $this->baseInstanceIndex(); }


    // /**
    //  * Displays a form to create a new City entity.
    //  *
    //  * @Route("/new", name="superadmin_city_new")
    //  */
    // public function new(): Response
    // { return $this->baseInstanceNew(); }


    // /**
    //  * Creates a new City entity.
    //  *
    //  * @Route("/create", name="superadmin_city_create", methods={"POST"})
    //  */
    // public function create(): Response
    // { return $this->baseInstanceCreate(); }


    // /**
    //  * Displays a form to edit an existing City entity.
    //  *
    //  * @Route("/{id}/edit", name="superadmin_city_edit")
    //  *
    //  * @param string $id The entity ID
    //  *
    //  * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
    //  */
    // public function edit(string $id): Response
    // { return $this->baseInstanceEdit($id); }


    // /**
    //  * Edits an existing City entity.
    //  *
    //  * @Route("/{id}/update", name="superadmin_city_update", methods={"POST"})
    //  *
    //  * @param string $id The entity ID
    //  *
    //  * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
    //  */
    // public function update(string $id): Response
    // { return $this->baseInstanceUpdate($id, 'superadmin_city'); }


    // /**
    //  * Batch actions.
    //  *
    //  * @Route("/batch", name="superadmin_city_batch")
    //  */
    // public function batch()
    // { return $this->baseBatch(); }


    // protected function batchUnion($element_ids): array
    // { return $this->baseUnion($element_ids); }


    // /**
    //  * Unifies a group of City entities.
    //  *
    //  * @Route("/doUnion", name="superadmin_city_doUnion", methods={"POST"})
    //  */
    // public function doUnion(): RedirectResponse
    // {
    //     $request = $this->requestStack->getCurrentRequest();
    //     $element_ids = $request->get('element');
    //     $main_id = $request->get('main');

    //     return $this->baseDoUnion(
    //         $element_ids, $main_id, 'superadmin_city'
    //     );
    // }
}
