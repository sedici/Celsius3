<?php

namespace Celsius\Celsius3Bundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAware;

class Builder extends ContainerAware
{

    public function topMenu(FactoryInterface $factory, array $options)
    {
        $request = $this->container->get('request');
        $securityContext = $this->container->get('security.context');

        $menu = $factory->createItem('root');
        $menu->setChildrenAttribute('class', 'nav');

        $menu->addChild('Home', array(
            'route' => 'public_index',
            'routeParameters' => array('url' => $request->attributes->get('url'))
        ));
        $menu->addChild('News', array(
            'route' => 'public_news',
            'routeParameters' => array('url' => $request->attributes->get('url'))
        ));
        if ($securityContext->isGranted('ROLE_ADMIN') !== false)
        {
            $menu->addChild('Administration', array(
                'route' => 'administration'
            ));
        }
        if ($securityContext->isGranted('ROLE_USER') !== false)
        {
            $menu->addChild('My Site', array(
                'route' => 'index'
            ));
        }
        $menu->addChild('Information', array(
            'route' => 'public_information',
            'routeParameters' => array('url' => $request->attributes->get('url'))
        ));
        $menu->addChild('Statistics', array(
            'route' => 'public_statistics',
            'routeParameters' => array('url' => $request->attributes->get('url'))
        ));
        if ($securityContext->isGranted('ROLE_USER') !== false)
        {
            $menu->addChild('Logout', array(
                'route' => 'fos_user_security_logout'
            ));
        } else
        {
            $menu->addChild('Login', array(
                'route' => 'fos_user_security_login'
            ));
            $menu->addChild('Register', array(
                'route' => 'fos_user_registration_register',
                'routeParameters' => array('url' => $request->attributes->get('url'))
            ));
        }

        return $menu;
    }

    public function directoryTopMenu(FactoryInterface $factory, array $options)
    {
        $request = $this->container->get('request');
        $securityContext = $this->container->get('security.context');

        $menu = $factory->createItem('root');
        $menu->setChildrenAttribute('class', 'nav');

        if ($securityContext->isGranted('ROLE_USER') !== false)
        {
            $menu->addChild('My Instance', array(
                'route' => 'public_index',
                'routeParameters' => array('url' => $request->attributes->get('url'))
            ));
            $menu->addChild('Logout', array(
                'route' => 'fos_user_security_logout'
            ));
        } else
        {
            $menu->addChild('Login', array(
                'route' => 'fos_user_security_login'
            ));
        }

        return $menu;
    }

    public function langSelectorMenu(FactoryInterface $factory, array $options)
    {
        $request = $this->container->get('request');

        $menu = $factory->createItem('root');
        $menu->setChildrenAttribute('class', 'nav pull-right');

        $menu->addChild('ES', array(
            'route' => $request->attributes->get('_route'),
            'routeParameters' => array_merge($request->attributes->get('_route_params'), array('_locale' => 'es')),
        ));
        $menu->addChild('EN', array(
            'route' => $request->attributes->get('_route'),
            'routeParameters' => array_merge($request->attributes->get('_route_params'), array('_locale' => 'en')),
        ));
        $menu->addChild('PT', array(
            'route' => $request->attributes->get('_route'),
            'routeParameters' => array_merge($request->attributes->get('_route_params'), array('_locale' => 'pt')),
        ));

        return $menu;
    }

    public function directoryContentMenu(FactoryInterface $factory, array $options)
    {
        $request = $this->container->get('request');

        $menu = $factory->createItem('root');
        $menu->setChildrenAttribute('class', 'nav nav-pills');

        $menu->addChild('Home', array(
            'route' => 'directory',
        ));
        $menu->addChild('Instances', array(
            'route' => 'directory_instances',
        ));

        return $menu;
    }

}
