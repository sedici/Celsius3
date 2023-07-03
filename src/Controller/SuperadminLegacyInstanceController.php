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

use Celsius3\Helper\ConfigurationHelper;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Celsius3\Entity\LegacyInstance;
use Celsius3\Form\Type\LegacyInstanceType;
use Celsius3\Form\Type\Filter\InstanceFilterType;
use Symfony\Component\HttpFoundation\Response;

/**
 * Instance controller.
 *
 * @Route("/superadmin/instance/legacy")
 */
class SuperadminLegacyInstanceController extends InstanceController
{
    /**
     * @var PaginatorInterface
     */
    private $paginator;

    public function __construct(
        PaginatorInterface $paginator,
        ConfigurationHelper $configurationHelper

    ) {
        $this->paginator = $paginator;

        $this->setConfigurationHelper($configurationHelper);

    }

    protected function getSortDefaults()
    {
        return array(
            'defaultSortFieldName' => 'e.name',
            'defaultSortDirection' => 'asc',
        );
    }

    /**
     * Lists all Instance entities.
     *
     * @Route("/", name="superadmin_instance_legacy")
     */
    public function index(): Response
    {
        return $this->render(
            'Superadmin/LegacyInstance/index.html.twig',
            $this->baseIndex('LegacyInstance', $this->createForm(InstanceFilterType::class),$this->paginator)
        );
    }

    /**
     * Displays a form to create a new LegacyInstance entity.
     *
     * @Route("/new", name="superadmin_instance_legacy_new")
     */
    public function new(): Response
    {
        return $this->render(
            'Superadmin/LegacyInstance/new.html.twig',
            $this->baseNew('LegacyInstance', new LegacyInstance(), LegacyInstanceType::class)
        );
    }

    /**
     * Creates a new LegacyInstance entity.
     *
     * @Route("/create", name="superadmin_instance_legacy_create", methods={"POST"})
     */
    public function create()
    {
        return $this->render(
            'Superadmin/LegacyInstance/new.html.twig',
            $this->baseCreate('LegacyInstance', new LegacyInstance(), LegacyInstanceType::class, array(), 'superadmin_instance_legacy'));
    }

    /**
     * Displays a form to edit an existing LegacyInstance entity.
     *
     * @Route("/{id}/edit", name="superadmin_instance_legacy_edit")
     *
     * @param string $id The entity ID
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function edit($id): Response
    {
        return $this->render(
            'Superadmin/LegacyInstance/edit.html.twig',
            $this->baseEdit('LegacyInstance', $id, LegacyInstanceType::class)
        );
    }

    /**
     * Edits an existing Instance entity.
     *
     * @Route("/{id}/update", name="superadmin_instance_legacy_update", methods={"POST"})
     *
     * @param string $id The entity ID
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function update($id)
    {
        return $this->render('Superadmin/LegacyInstance/edit.html.twig', $this->baseUpdate('LegacyInstance', $id, LegacyInstanceType::class, array(), 'superadmin_instance_legacy'));
    }
}
