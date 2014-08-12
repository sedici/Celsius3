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

namespace Celsius3\MigrationBundle\Twig;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Celsius\MigrationBundle\Document\Country;
use Celsius\MigrationBundle\Document\City;
use Celsius\MigrationBundle\Document\Institution;

class RenderingExtension extends \Twig_Extension
{
    private $environment;
    private $container;

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
                'render_country' => new \Twig_Function_Method($this,
                        'renderCountry'),
                'render_city' => new \Twig_Function_Method($this, 'renderCity'),
                'render_institution' => new \Twig_Function_Method($this,
                        'renderInstitution'),);
    }

    public function renderCountry(Country $country)
    {
        return $this->environment
                ->render('CelsiusMigrationBundle:Default:_country.html.twig',
                        array('country' => $country,
                                'cities' => $this->container
                                        ->get(
                                                'doctrine.odm.mongodb.document_manager')
                                        ->getRepository(
                                                'CelsiusMigrationBundle:City')
                                        ->findBy(
                                                array(
                                                        'country.id' => $country
                                                                ->getId())),
                                'institutions' => $this->container
                                        ->get(
                                                'doctrine.odm.mongodb.document_manager')
                                        ->getRepository(
                                                'CelsiusMigrationBundle:Institution')
                                        ->findBy(
                                                array(
                                                        'country.id' => $country
                                                                ->getId(),
                                                        'city.id' => null,)),));
    }

    public function renderCity(City $city)
    {
        return $this->environment
                ->render('CelsiusMigrationBundle:Default:_city.html.twig',
                        array('city' => $city,
                                'institutions' => $this->container
                                        ->get(
                                                'doctrine.odm.mongodb.document_manager')
                                        ->getRepository(
                                                'CelsiusMigrationBundle:Institution')
                                        ->findBy(
                                                array(
                                                        'city.id' => $city
                                                                ->getId())),));
    }

    public function renderInstitution(Institution $institution)
    {
        return $this->environment
                ->render(
                        'CelsiusMigrationBundle:Default:_institution.html.twig',
                        array('institution' => $institution,
                                'institutions' => $this->container
                                        ->get(
                                                'doctrine.odm.mongodb.document_manager')
                                        ->getRepository(
                                                'CelsiusMigrationBundle:Institution')
                                        ->findBy(
                                                array(
                                                        'parent.id' => $institution
                                                                ->getId())),));
    }

    public function getName()
    {
        return 'rendering_extension';
    }
}