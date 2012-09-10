<?php

namespace Celsius\Celsius3Bundle\Listener;

use JMS\I18nRoutingBundle\Router\LocaleResolverInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Celsius\Celsius3Bundle\Helper\ConfigurationHelper;

class LanguageListener
{

    private $defaultLocale;
    private $locales;
    private $localeResolver;
    private $container;
    private $dm;

    public function __construct($defaultLocale, array $locales, LocaleResolverInterface $localeResolver, ContainerInterface $container, DocumentManager $dm)
    {
        $this->defaultLocale = $defaultLocale;
        $this->locales = $locales;
        $this->localeResolver = $localeResolver;
        $this->container = $container;
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

        $locale = $this->localeResolver->resolveLocale($request, $this->locales) ? : $this->defaultLocale;

        $path = explode('/', $request->getPathInfo());

        if (!array_key_exists($path[1], ConfigurationHelper::$languages) && !$this->container->get('request')->isXmlHttpRequest())
        {
            if ($this->container->get('session')->has('instance_url'))
                $instance_url = $this->container->get('session')->get('instance_url');
            else
                $instance_url = $path[1];

            $instance = $this->dm
                    ->getRepository('CelsiusCelsius3Bundle:Instance')
                    ->findOneBy(array('url' => $instance_url));

            if ($instance)
                $locale = $instance->get('instance_language')->getValue();
        }

        $request->setLocale($locale);

        $params = $request->query->all();
        unset($params['hl']);

        $event->setResponse(new RedirectResponse($request->getBaseUrl() . '/' . $locale . $request->getPathInfo() . ($params ? '?' . http_build_query($params) : '')));
    }

}
