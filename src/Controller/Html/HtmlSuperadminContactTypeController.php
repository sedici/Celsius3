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
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Celsius3.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Celsius3\Controller\Html;

use Symfony\Component\HttpFoundation\Response;
use Celsius3\Controller\Base\ContactTypeController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Routing\Annotation\Route;


#[
    Route('/superadmin/contacttype'),
    IsGranted(data: 'ROLE_SUPER_ADMIN')
]
class HtmlSuperadminContactTypeController extends ContactTypeController
{

    public function initialize(): void
    {
        parent::initialize();
        $this->htmlRenderer->setTemplatePrefix('Superadmin/ContactType/');
        $this->setInstanceDependent(false);
        $this->setInstance($this->directory);
    }

    #[Route(
        "/",
        name: "superadmin_contacttype"
    )]
    public function htmlIndex(): Response
    {
        return $this->htmlRenderer->render(
            'index',
            $this->index(hasFilterForm: false)
        );
     }

    #[Route(
        "/new",
        name: "superadmin_contacttype_new"
    )]
    public function htmlNew(): Response
    {
        return $this->htmlRenderer->render(
            'new',
            $this->new()
        );
    }

    #[Route(
        "/create",
        name: "superadmin_contacttype_create",
        methods: ["POST"]
    )]
    public function htmlCreate(): Response
    {
        return $this->htmlRenderer->render(
            '../Contact/new',
            $this->create(redirectRoute: 'superadmin_contacttype')
        );
    }

    #[Route(
        "/{id}/edit",
        name: "superadmin_contacttype_edit"
    )]
    public function htmlEdit(string $id): Response
    {
        return $this->htmlRenderer->render(
            'edit',
            $this->edit($id)
        );
    }

    #[Route(
        "/{id}/update",
        name: "superadmin_contacttype_update",
        methods: ["POST"]
    )]
    public function htmlUpdate(string $id): Response
    {
        return $this->htmlRenderer->render(
            'edit',
            $this->update($id, 'superadmin_contacttype_edit')
        );
    }
}
