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

namespace Celsius3\EventListener;

use JMS\I18nRoutingBundle\Router\LocaleResolverInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Celsius3\Helper\InstanceHelper;

class LocaleChoosingListener
{
    private $default_locale;
    private $locales;
    private $locale_resolver;
    private $instance_helper;

    public function __construct($default_locale, array $locales, LocaleResolverInterface $locale_resolver, InstanceHelper $instance_helper)
    {
        $this->default_locale = $default_locale;
        $this->locales = $locales;
        $this->locale_resolver = $locale_resolver;
        $this->instance_helper = $instance_helper;
    }

    public function onKernelException(ExceptionEvent $event)
    {
        if (HttpKernelInterface::MASTER_REQUEST !== $event->getRequestType()) {
            return;
        }
        
        $request = $event->getRequest();
        if ('' !== rtrim($request->getPathInfo(), '/')) {
            return;
        }
        
        $ex = $event->getThrowable();
        if (!$ex instanceof NotFoundHttpException || !$ex->getPrevious() instanceof ResourceNotFoundException) {
            return;
        }

        $locale = $this->locale_resolver->resolveLocale($request, $this->locales) ? : $this->default_locale;

        if (!$request->attributes->get('_locale') && !$request->isXmlHttpRequest()) {
            $instance = $this->instance_helper->getSessionOrUrlInstance();

            if ($instance) {
                $locale = $instance->get('default_language')->getValue();
            }
        }

        $request->setLocale($locale);

        $params = $request->query->all();
        unset($params['hl']);

        $event->setResponse(new RedirectResponse($request->getBaseUrl() . '/' . $locale . $request->getPathInfo() . ($params ? '?' . http_build_query($params) : '')));
    }
}
