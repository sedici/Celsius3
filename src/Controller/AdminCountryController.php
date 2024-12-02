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

use Celsius3\Entity\Instance;
use Celsius3\Exception\Exception;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Celsius3\Entity\Country;
use Celsius3\Form\Type\CountryType;
use Celsius3\Form\Type\Filter\CountryFilterType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;


/**
 * Order controller.
 *
 * @Route("/admin/country")
 */
class AdminCountryController extends BaseInstanceDependentController
{

    protected final function getEntity(): string
    { return Country::class; }

    protected final function getType(): string
    { return CountryType::class; }

    protected final function getTemplatePrefix(): string
    { return 'Admin/Country/'; }


    protected function getDirectory()
    {
        return $this->managerRegistry
            ->getRepository(Instance::class)
            ->findOneBy([
                'url' => 'directory'
            ]);
    }

    protected function getSortDefaults(): array
    {
        return [
            'defaultSortFieldName' => 'e.name',
            'defaultSortDirection' => 'asc',
        ];
    }

    /**
     * Lists all Country entities.
     *
     * @Route("/", name="admin_country")
     */
    public function index(): Response
    {
        return $this->baseInstanceIndex();
    }

    /**
     * Displays a form to create a new Country entity.
     *
     * @Route("/new", name="admin_country_new")
     */
    public function new(): Response
    {
        return $this->baseInstanceNew();
    }

    /**
     * Creates a new Country entity.
     *
     * @Route("/create", name="admin_country_create", methods={"POST"})
     */
    public function create(): RedirectResponse|Response
    {
        return $this->baseInstanceCreate(route: 'admin_country');
    }


    /**
     * Displays a form to edit an existing Country entity.
     *
     * @Route("/{id}/edit", name="admin_country_edit")
     *
     * @param string $id The entity ID
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function edit($id): Response
    {
        return $this->baseInstanceEdit($id);
    }

    /**
     * Edits an existing Country entity.
     *
     * @Route("/{id}/update", name="admin_country_update", methods={"POST"})
     *
     * @param string $id The entity ID
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function update($id): RedirectResponse|Response
    {
        return $this->baseInstanceUpdate($id, 'admin_country');
    }
}
