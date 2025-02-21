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

use Celsius3\Controller\Base\ContactController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


#[
    Route('/rest/v1/admin/contact'),
    IsGranted('ROLE_ADMIN')
]
class RestAdminContactController extends ContactController
{

    public function initialize(): void
    {
        parent::initialize();
        $this->setInstanceDependent(false);
    }


    #[Route('/byInstitution/{id}', name: 'rest_admin_contact', options: ['expose' => true])]
    public function restShowByInstitution(string $id): Response
    {
        $contacts = $this->repository->findBy([ 'institution' => $id ]);
        return $this->restRenderer->render(
            $contacts, serializerGroups: 'administration'
        );
    }


    #[Route('/{id}/show', name: 'rest_admin_contact', options: ['expose' => true])]
    public function restShow(string $id): Response
    { return $this->restRenderer->show($id, 'administration'); }
}
