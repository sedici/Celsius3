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

namespace Celsius3\CoreBundle\Form\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityManager;
use Celsius3\CoreBundle\Repository\CityRepository;
use Celsius3\CoreBundle\Repository\CountryRepository;
use Celsius3\CoreBundle\Repository\InstitutionRepository;

class AddInstitutionFieldsSubscriber implements EventSubscriberInterface
{
    private $factory;
    private $em;
    private $property_path;
    private $required;
    private $country_mapped;
    private $city_mapped;
    private $with_filter;

    public function __construct(FormFactoryInterface $factory, EntityManager $em, $property_path = 'institution', $required = true, $country_mapped = false, $city_mapped = false, $with_filter = false)
    {
        $this->factory = $factory;
        $this->em = $em;
        $this->property_path = $property_path;
        $this->required = $required;
        $this->country_mapped = $country_mapped;
        $this->city_mapped = $city_mapped;
        $this->with_filter = $with_filter;
    }

    public static function getSubscribedEvents()
    {
        return array(
            FormEvents::PRE_SET_DATA => 'preSetData',
            FormEvents::PRE_SUBMIT => 'preBind',
        );
    }

    private function addInstitutionFields(FormEvent $event, $bind)
    {
        $data = $event->getData();
        $form = $event->getForm();

        $country = null;
        $city = null;
        $institution = null;

        if ($bind && array_key_exists($this->property_path, $data)) {
            $institution = $this->em->getRepository('Celsius3CoreBundle:Institution')
                    ->find($data[$this->property_path]);
        } elseif (is_object($data)) {
            $function = 'get'.ucfirst($this->property_path);
            $institution = $data->$function();
        }

        if ($institution) {
            $city = $institution->getCity();
            if (is_null($city)) {
                $country = $institution->getCountry();
            } else {
                $country = $city->getCountry();
            }
        } elseif (is_object($data) && ($data instanceof \Celsius3\CoreBundle\Entity\Institution)) {
            $city = $data->getCity();
            $country = $data->getCountry();
        } elseif (is_array($data) && array_key_exists('city', $data) && array_key_exists('country', $data)) {
            $city = $this->em->getRepository('Celsius3CoreBundle:City')->find($data['city']);
            $country = $this->em->getRepository('Celsius3CoreBundle:Country')->find($data['country']);
        }

        if ($this->with_filter) {
            $filter = null;
            $form->add($this->factory->createNamed('filter', ChoiceType::class, $filter, array(
                        'choices' => array(
                            'liblink' => 'Liblink',
                            'celsius3' => 'Celsius3',
                        ),
                        'mapped' => false,
                        'placeholder' => 'All',
                        'required' => false,
                        'expanded' => true,
                        'attr' => array(
                            'class' => 'filter-select',
                        ),
                        'auto_initialize' => false,
            )));
        }

        $form->add($this->factory->createNamed('country', EntityType::class, $country, array(
                    'class' => 'Celsius3CoreBundle:Country',
                    'mapped' => $this->country_mapped,
                    'placeholder' => '',
                    'required' => false,
                    'query_builder' => function (CountryRepository $cr) {
                        return $cr->getAllOrderedByNameQB();
                    },
                    'attr' => array(
                        'class' => 'country-select',
                    ),
                    'auto_initialize' => false,
                    'choice_label' => function ($category) {
                        return $this->firstUpper($category);
                    }

        )));

        $form->add($this->factory->createNamed('city', EntityType::class, $city, array(
                    'class' => 'Celsius3CoreBundle:City',
                    'mapped' => $this->city_mapped,
                    'placeholder' => '',
                    'required' => false,
                    'query_builder' => function (CityRepository $cr) use ($country) {
                        return $cr->findForCountryQB($country);
                    },
                    'attr' => array(
                        'class' => 'city-select',
                    ),
                    'auto_initialize' => false,
                    'choice_label' => function ($category) {
                        return $this->firstUpper($category);
                    }
        )));

        $form->add($this->factory->createNamed($this->property_path, EntityType::class, $institution, array(
                    'class' => 'Celsius3CoreBundle:Institution',
                    'property_path' => $this->property_path,
                    'label' => ucfirst($this->property_path),
                    'placeholder' => '',
                    'required' => $this->required,
                    'query_builder' => function (InstitutionRepository $ir) use ($city, $country) {
                        return $ir->findByCountryAndCityQB($country, $city);
                    },
                    'attr' => array(
                        'class' => 'institution-select',
                    ),
                    'auto_initialize' => false,
        )));
    }

    public function preSetData(FormEvent $event)
    {
        $this->addInstitutionFields($event, false);
    }

    public function preBind(FormEvent $event)
    {
        $this->addInstitutionFields($event, true);
    }

    private function firstUpper($category)
    {
        if (!is_string($category)){
            return $category;
        }

        $words = explode(' ', $category);

        $t = '';
        foreach ($words as $k => $word) {
            if (strlen($word) > 3 || $k === 0 ) {
                $t .= ucfirst(mb_strtolower($word)) . ' ';
            } else {
                $t .= mb_strtolower($word) . ' ';
            }
        }

        return $t;
    }
}
