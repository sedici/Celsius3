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

namespace Celsius3\Controller\Rest;

use Celsius3\Controller\Base\JournalController;
use Celsius3\Entity\Instance;
use Celsius3\Entity\JournalType;
use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\Controller\Annotations\Post;
use Celsius3\Entity\Journal;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;


#[
    Route('/rest/v1/admin/journal'),
    IsGranted('ROLE_ADMIN')
]
class RestAdminJournalController extends JournalController
{
    #[Route(
        '/create',
        name: 'admin_rest_journal_create',
        options: [ 'expose' => true ],
        methods: ['POST', 'GET']
    )]
    public function createJournal(): Response
    {
        $request = $this->requestStack->getCurrentRequest();

        $journal = new Journal();
        $journal->setName($request->request->get('name'));
        $journal->setAbbreviation($request->request->get('abbreviation'));
        $journal->setResponsible($request->request->get('responsible'));
        $journal->setISSN($request->request->get('issn'));
        $journal->setISSNE($request->request->get('issne'));
        $journal->setInstance($this->instance);


        $errors = $this->validator->validate($journal);

        if (count($errors) > 0) {
            return $this->restRenderer->render(['hasErrors' => true, 'errors' => $errors]);
        }

        $this->persistEntity($journal);

        $material = $this->entityManager
            ->getRepository(JournalType::class)
            ->find(
                $request->request->get('material_type_id')
            );
        $material->setJournal($journal);

        $this->persistEntity($material);

        return $this->restRenderer->render(['hasErrors' => false, 'journal' => $journal]);
    }
}
