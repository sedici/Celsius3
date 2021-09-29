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

use Celsius3\CoreBundle\Entity\Event\Event;
use Celsius3\CoreBundle\Entity\Event\MultiInstanceRequestEvent;
use Celsius3\CoreBundle\Entity\Event\SearchEvent;
use Celsius3\CoreBundle\Entity\Request;
use Celsius3\Manager\CatalogManager;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

use function count;

class EventExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('get_request_state', [$this, 'getRequestState']),
            new TwigFunction('count_searches', [$this, 'countSearches']),
            new TwigFunction('has_requests', [$this, 'hasRequests']),
        ];
    }

    public function getRequestState(MultiInstanceRequestEvent $request)
    {
        return $request->getOrder()->getCurrentState($request->getRemoteInstance());
    }

    public function countSearches(Request $request): int
    {
        return $request->getEvents()->filter(
            static function ($e) {
                return $e instanceof SearchEvent && $e->getResult() !== CatalogManager::CATALOG__NON_SEARCHED;
            }
        )->count();
    }

    public function hasRequests($events): bool
    {
        $requests = array_filter(
            $events->toArray(),
            static function (Event $e) {
                return $e->getEventType() === 'sirequest' || $e->getEventType() === 'mirequest';
            }
        );

        return count($requests) > 0;
    }

    public function getName(): string
    {
        return 'celsius3_core.event_extension';
    }
}
