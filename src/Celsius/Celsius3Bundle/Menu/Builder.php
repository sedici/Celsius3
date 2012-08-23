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

        $instance_url = $request->attributes->has('url') ? $request->attributes->get('url') : $this->container->get('session')->get('instance_url');

        $menu = $factory->createItem('root');
        $menu->setChildrenAttribute('class', 'nav');

        $menu->addChild('Home', array(
            'route' => 'public_index',
            'routeParameters' => array('url' => $instance_url)
        ));
        if ($securityContext->isGranted('ROLE_ADMIN') !== false)
        {
            $menu->addChild('Administration', array(
                'route' => 'administration'
            ));
        }
        if ($securityContext->isGranted('ROLE_SUPER_ADMIN') !== false)
        {
            $menu->addChild('Super Administration', array(
                'route' => 'superadministration'
            ));
        }
        if ($securityContext->isGranted('ROLE_USER') !== false)
        {
            $menu->addChild('My Site', array(
                'route' => 'user_index'
            ));
        }

        return $menu;
    }

    public function directoryMenu(FactoryInterface $factory, array $options)
    {
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

    public function publicMenu(FactoryInterface $factory, array $options)
    {
        $request = $this->container->get('request');

        $instance_url = $request->attributes->has('url') ? $request->attributes->get('url') : $this->container->get('session')->get('instance_url');

        $menu = $factory->createItem('root');
        $menu->setChildrenAttribute('class', 'nav nav-pills');

        $menu->addChild('Home', array(
            'route' => 'public_index',
            'routeParameters' => array('url' => $instance_url)
        ));
        $menu->addChild('News', array(
            'route' => 'public_news',
            'routeParameters' => array('url' => $instance_url)
        ));
        $menu->addChild('Information', array(
            'route' => 'public_information',
            'routeParameters' => array('url' => $instance_url)
        ));
        $menu->addChild('Statistics', array(
            'route' => 'public_statistics',
            'routeParameters' => array('url' => $instance_url)
        ));

        return $menu;
    }

}
