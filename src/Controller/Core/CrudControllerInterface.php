<?php

/*
 * Celsius3 - Crud controller contract
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

namespace Celsius3\Controller\Core;

use Symfony\Component\HttpFoundation\RedirectResponse;

interface CrudControllerInterface
{
    public function index(): array|RedirectResponse;
    public function show(string $id): array|RedirectResponse;
    public function edit(string $id): array|RedirectResponse;
    public function update(string $id): array|RedirectResponse;
    public function new(): array|RedirectResponse;
    public function create(): array|RedirectResponse;
    public function delete(string $id): array|RedirectResponse;
}