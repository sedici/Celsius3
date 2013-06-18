<?php

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
