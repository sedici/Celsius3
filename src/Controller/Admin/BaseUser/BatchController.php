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

declare(strict_types=1);

namespace Celsius3\Controller\Admin\BaseUser;

use Celsius3\Controller\BaseUserController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

final class BatchController extends BaseUserController
{
    public function getTemplatePrefix(): string
    { return '/Admin/BaseUser/'; }


    public function __invoke(): mixed
    {
        return $this->baseBatch();
    }

    protected function batchEnable($element_ids): RedirectResponse
    {
        return $this->baseBatchEnable($element_ids);
    }

    protected function batchUnion($element_ids): Response
    {
        return $this->render(
            (string) $this->templatePrefix . 'batchUnion.html.twig',
            $this->baseUnion($element_ids)
        );
    }
}
