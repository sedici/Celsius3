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

namespace Celsius3\EventListener;

use Celsius3\Helper\InstanceHelper;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class LocaleChoosingListener
{
    private InstanceHelper $instanceHelper;

    public function __construct(InstanceHelper $instanceHelper)
    {
        $this->instanceHelper = $instanceHelper;
    }

    public function onKernelRequest(RequestEvent $event)
    {
        $request = $event->getRequest();

        if ($locale = $request->attributes->get('_locale')) {
            $request->getSession()->set('_locale', $locale);
        } elseif ($locale = $request->getSession()->get('_locale')) {
            $request->setLocale($locale);
        } elseif ($instance = $this->instanceHelper->getSessionOrUrlInstance()) {
            $locale = $instance->get('default_language')->getValue();
            $request->setLocale($locale);
            $request->getSession()->set('_locale', $locale);
        } else {
            $locale = 'en';
            $request->setLocale($locale);
            $request->getSession()->set('_locale', $locale);
        }
    }
}
