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

use Symfony\Component\HttpFoundation\Response;
use Celsius3\Controller\Base\EmailController;
use Celsius3\Form\Type\Filter\MailFilterType;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/superadmin/email')]
class HtmlSuperadminEmailController extends EmailController
{

    public function initialize(): void
    {
        parent::initialize();
        $this->setInstance($this->directory);
        $this->setInstanceDependent(false);
        $this->htmlRenderer->setTemplatePrefix('Superadmin/MailList/');
    }


    #[Route('/', name: 'superadmin_maillist')]
    public function htmlIndex(): Response
    {
        return $this->htmlRenderer->render(
            'index', $this->index(MailFilterType::class)
        );
    }
}
