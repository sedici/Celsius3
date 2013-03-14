<?php

namespace Celsius\Celsius3Bundle\Listener;

use JMS\I18nRoutingBundle\Router\LocaleResolverInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Doctrine\ODM\MongoDB\DocumentManager;
use Celsius\Celsius3Bundle\Helper\InstanceHelper;

class LocaleChoosingListener
{

    private $default_locale;
    private $locales;
    private $locale_resolver;
    private $instance_helper;
    private $dm;

    public function __construct($default_locale, array $locales, LocaleResolverInterface $locale_resolver, InstanceHelper $instance_helper, DocumentManager $dm)
    {
        $this->default_locale = $default_locale;
        $this->locales = $locales;
        $this->locale_resolver = $locale_resolver;
        $this->instance_helper = $instance_helper;
        $this->dm = $dm;
    }

    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        if (HttpKernelInterface::MASTER_REQUEST !== $event->getRequestType())
        {
            return;
        }

        $request = $event->getRequest();

        $ex = $event->getException();
        if (!$ex instanceof NotFoundHttpException || !$ex->getPrevious() instanceof ResourceNotFoundException)
        {
            return;
        }

        $locale = $this->locale_resolver->resolveLocale($request, $this->locales) ? : $this->default_locale;

        if (!$request->attributes->get('_locale') && !$this->request->isXmlHttpRequest())
        {
            $instance = $this->instance_helper->getSessionOrUrlInstance();

            if ($instance)
                $locale = $instance->get('instance_language')->getValue();
        }

        $request->setLocale($locale);

        $params = $request->query->all();
        unset($params['hl']);

        $event->setResponse(new RedirectResponse($request->getBaseUrl() . '/' . $locale . $request->getPathInfo() . ($params ? '?' . http_build_query($params) : '')));
    }

}
