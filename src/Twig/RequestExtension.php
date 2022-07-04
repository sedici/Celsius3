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

namespace Celsius3\Twig;

use Celsius3\Entity\Request;
use Celsius3\Entity\State;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

use function count;

class RequestExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('search_pending', [$this, 'searchPending']),
        ];
    }

    public function searchPending(Request $request): bool
    {
        $array = array_filter(
            $request->getStates()->toArray(),
            static function (State $item) {
                return $item->getType() === 'requested' && $item->getSearchPending();
            }
        );

        return count($array) > 0;
    }

    public function getName(): string
    {
        return 'celsius3_core.request_extension';
    }
}
