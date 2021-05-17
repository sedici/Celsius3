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

namespace Celsius3\Controller\Admin\Catalog;

use Celsius3\CoreBundle\Entity\Catalog;
use Celsius3\CoreBundle\Form\Type\CatalogType;
use Celsius3\CoreBundle\Helper\InstanceHelper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

final class NewCatalogGetController extends AbstractController
{
    private $instanceHelper;

    public function __construct(InstanceHelper $instanceHelper)
    {
        $this->instanceHelper = $instanceHelper;
    }

    public function __invoke(): Response
    {
        $catalog = new Catalog();

        $form = $this->createForm(
            CatalogType::class,
            $catalog,
            [
                'instance' => $this->instanceHelper->getSessionInstance(),
            ]
        );

        return $this->render(
            'Admin/Catalog/new.html.twig',
            [
                'entity' => $catalog,
                'form' => $form->createView(),
            ]
        );
    }
}
