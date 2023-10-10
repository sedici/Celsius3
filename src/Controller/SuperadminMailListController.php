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

use Celsius3\Form\Type\Filter\MailFilterType;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Celsius3\Entity\Email;
/**
 * MailList controller.
 *
 * @Route("/superadmin/maillist")
 */
class SuperadminMailListController extends BaseController
{


    protected function listQuery($name)
    {
        $valor=$name;
        return $this->getDoctrine()->getManager()
            ->getRepository(Email::class)
            ->createQueryBuilder('e');
    }

    /**
     * Lists all Mail entities.
     *
     * @Route("/", name="superadmin_maillist")
     */
    public function index(PaginatorInterface $paginator): Response
    {
        return $this->render(
            'Superadmin/MailList/index.html.twig',
            $this->baseIndex('Email', $this->createForm(MailFilterType::class),$paginator)
        );
    }
}
