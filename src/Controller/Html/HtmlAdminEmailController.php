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

namespace Celsius3\Controller\Html;

use Celsius3\Controller\Base\EmailController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


#[
    Route('/admin/email'),
    IsGranted('ROLE_ADMIN')
]
class HtmlAdminEmailController extends EmailController
{

    public function initialize(): void
    {
        parent::initialize();
        $this->setInstanceDependent(true);
    }


    #[Route('/', name: 'admin_email')]
    public function htmlIndex(): Response
    { return $this->htmlRenderer->render('index', $this->index()); }


    #[Route('/new', name: 'admin_email_new')]
    public function htmlNew(): Response
    { return $this->htmlRenderer->render('new', $this->new()); }


    #[Route('/create', name: 'admin_email_create', methods: ['POST'])]
    public function htmlCreate(): Response
    { return $this->htmlRenderer->render('new', $this->create()); }
}
