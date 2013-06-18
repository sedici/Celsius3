<?php

namespace Celsius3\MessageBundle\Menu;
use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAware;

class Builder extends ContainerAware
{

    public function messageMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');
        $menu->setChildrenAttribute('class', 'nav nav-pills');

        $menu
                ->addChild('Inbox',
                        array('route' => 'fos_message_inbox',));
        $menu
                ->addChild('Sent',
                        array('route' => 'fos_message_sent',));

        return $menu;
    }

}
