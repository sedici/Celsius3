<?php

namespace Celsius3\CoreBundle\Menu;
use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAware;
use Celsius3\CoreBundle\Manager\UserManager;

class Builder extends ContainerAware
{

    public function topMenu(FactoryInterface $factory, array $options)
    {
        $request = $this->container->get('request');
        $securityContext = $this->container->get('security.context');

        $instance_url = $request->attributes->has('url') ? $request->attributes
                        ->get('url')
                : $this->container->get('session')->get('instance_url');

        $local = $request->attributes->has('url')
                && $request->attributes->get('url')
                        == $this->container->get('session')
                                ->get('instance_url')
                || !$request->attributes->has('url');

        $menu = $factory->createItem('root');
        $menu->setChildrenAttribute('class', 'nav-main');

        $menu
                ->addChild('Home',
                        array('route' => 'public_index',
                                'routeParameters' => array(
                                        'url' => $instance_url)));
        if ($securityContext->isGranted(UserManager::ROLE_ADMIN) !== false
                && $local) {
            $menu
                    ->addChild('Administration',
                            array('route' => 'administration'));
        }
        if ($securityContext->isGranted(UserManager::ROLE_SUPER_ADMIN)
                !== false && $local) {
            $menu
                    ->addChild('Network Administration',
                            array('route' => 'superadministration'));
        }
        if ($securityContext->isGranted(UserManager::ROLE_USER) !== false
                && $local) {
            $menu->addChild('My Site', array('route' => 'user_index'));
        }
        if ($securityContext->isGranted(UserManager::ROLE_USER) !== false
                && !$local) {
            $menu
                    ->addChild('My Instance',
                            array('route' => 'public_index',
                                    'routeParameters' => array(
                                            'url' => $this->container
                                                    ->get('session')
                                                    ->get('instance_url'))));
        }

        return $menu;
    }

    public function directoryMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');
        $menu->setChildrenAttribute('class', 'nav-pills');

        $menu->addChild('Home', array('route' => 'directory',));
        $menu
                ->addChild('Instances',
                        array('route' => 'directory_instances',));

        return $menu;
    }

    public function publicMenu(FactoryInterface $factory, array $options)
    {
        $request = $this->container->get('request');

        $instance_url = $request->attributes->has('url') ? $request->attributes
                        ->get('url')
                : $this->container->get('session')->get('instance_url');

        $menu = $factory->createItem('root');
        $menu->setChildrenAttribute('class', 'nav-pills');

        $menu
                ->addChild('Home',
                        array('route' => 'public_index',
                                'routeParameters' => array(
                                        'url' => $instance_url)));
        $menu
                ->addChild('News',
                        array('route' => 'public_news',
                                'routeParameters' => array(
                                        'url' => $instance_url)));
        $menu
                ->addChild('Information',
                        array('route' => 'public_information',
                                'routeParameters' => array(
                                        'url' => $instance_url)));
        $menu
                ->addChild('Statistics',
                        array('route' => 'public_statistics',
                                'routeParameters' => array(
                                        'url' => $instance_url)));

        return $menu;
    }

}
