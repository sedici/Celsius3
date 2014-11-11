<?php

/*
 * Celsius3 - Order management
 * Copyright (C) 2014 PrEBi <info@prebi.unlp.edu.ar>
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

namespace Celsius3\CoreBundle\Twig;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Celsius3\CoreBundle\Entity\Request;

class StateExtension extends \Twig_Extension
{
    private $container;
    private $environment;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function initRuntime(\Twig_Environment $environment)
    {
        $this->environment = $environment;
    }

    public function getFunctions()
    {
        return array(
            'render_state_header' => new \Twig_Function_Method($this, 'renderStateHeader'),
            'get_positive_states' => new \Twig_Function_Method($this, 'getPositiveStates'),
            'render_state_body' => new \Twig_Function_Method($this, 'renderStateBody'),
        );
    }

    public function renderStateHeader($state, Request $request)
    {
        return $this->environment->render('Celsius3CoreBundle:AdminOrder:_state.html.twig', $this->container->get('celsius3_core.state_manager')->getDataForHeaderRendering($state, $request));
    }

    public function renderStateBody($state, Request $request)
    {
        return $this->environment->render('Celsius3CoreBundle:AdminOrder:_info_' . $state . '.html.twig', $this->container->get('celsius3_core.state_manager')->getDataForBodyRendering($state, $request));
    }

    public function getPositiveStates()
    {
        return $this->container->get('celsius3_core.state_manager')->getPositiveStates();
    }

    public function getName()
    {
        return 'celsius3_core.state_extension';
    }
}