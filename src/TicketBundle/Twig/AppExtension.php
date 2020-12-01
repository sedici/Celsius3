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

namespace Celsius3\TicketBundle\Twig;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Routing\Router;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    protected $entityManager;
    protected $router;
    protected $kernel;

    public function __construct(EntityManager $entityManager, Router $router, Kernel $kernel)
    {
        $this->entityManager = $entityManager;
        $this->router = $router;
        $this->kernel = $kernel;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('estilos_estado', [$this, 'getEstiloEstado'], ['is_safe' => ['html']]),
        ];
    }

    public function getEstiloEstado($filter): string
    {
        switch ($filter) {
            case '1':
                $name = 'label-danger';
                break;

            case '2':
                $name = 'label-info';
                break;

            case '3':
                $name = 'label-success';
                break;

            case '4':
                $name = 'label-warning';
                break;

            default:
                $name = '';
        }

        return $name;
    }

    public function getName(): string
    {
        return 'app_extension';
    }
}
