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
use Celsius3\Entity\JournalType;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\Controller\Annotations\Post;
use Celsius3\Entity\Journal;

/**
 * Journal rest controller.
 *
 * @Route("/admin/rest/journal")
 */
class AdminJournalRestController extends BaseInstanceDependentRestController
{
    /**
     * @Post("/create", name="admin_rest_journal_create", options={"expose"=true})
     */
    public function createJournal(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $journal = new Journal();
        $journal->setName($request->request->get('name'));
        $journal->setAbbreviation($request->request->get('abbreviation'));
        $journal->setResponsible($request->request->get('responsible'));
        $journal->setISSN($request->request->get('issn'));
        $journal->setISSNE($request->request->get('issne'));
        $journal->setInstance($em->getRepository(Instance::class)->find($request->request->get('instance')));

        $validator = $this->get('validator');
        $errors = $validator->validate($journal);

        if (count($errors) > 0) {
            $view = $this->view(array('hasErrors' => true, 'errors' => $errors), 200)->setFormat('json');

            return $this->handleView($view);
        }

        $em->persist($journal);
        $em->flush($journal);

        $material = $em->getRepository(JournalType::class)->find($request->request->get('material_type_id'));
        $material->setJournal($journal);

        $em->persist($material);
        $em->flush($material);

        $view = $this->view(array('hasErrors' => false, 'journal' => $journal), 200)->setFormat('json');

        return $this->handleView($view);
    }
}
