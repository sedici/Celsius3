<?php
declare(strict_types=1);

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

namespace Celsius3\Controller\Admin\FileDownload;

use Celsius3\Controller\BaseInstanceDependentController;
use Celsius3\Form\Type\Filter\FileDownloadFilterType;
use Symfony\Component\HttpFoundation\Response;

class ListAllFileDownloadViewController extends BaseInstanceDependentController
{
    public function __invoke(): ?Response
    {
        $filter_form = $this->createForm(
            FileDownloadFilterType::class,
            null,
            [
                'instance' => $this->getInstance(),
            ]
        );

        $query = $this->listQuery('FileDownload');
        $request = $this->get('request_stack')->getCurrentRequest();
        if ($filter_form !== null) {
            $filter_form = $filter_form->handleRequest($request);
            $query = $this->filter('FileDownload', $filter_form, $query);
        }

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $request->query->get('page', 1),
            $this->getResultsPerPage(),
            $this->getSortDefaults()
        );

        return $this->render(
            'Admin/FileDownload/index.html.twig',
            [
                'pagination' => $pagination,
                'filter_form' => ($filter_form !== null) ? $filter_form->createView() : $filter_form,
            ]
        );
    }
}