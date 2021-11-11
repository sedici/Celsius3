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

namespace Celsius3\Form\EventListener;

use Celsius3\CoreBundle\Entity\City;
use Celsius3\CoreBundle\Entity\Country;
use Celsius3\CoreBundle\Entity\Institution;
use Celsius3\CoreBundle\Repository\CityRepository;
use Celsius3\CoreBundle\Repository\CountryRepository;
use Celsius3\CoreBundle\Repository\InstitutionRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormFactoryInterface;

class AddInstitutionFieldsSubscriber implements EventSubscriberInterface
{
    private $factory;
    private $em;
    private $property_path;
    private $required;
    private $country_mapped;
    private $city_mapped;
    private $with_filter;
    private $showCity;

    public function __construct(FormFactoryInterface $factory, EntityManager $em, $property_path = 'institution', $required = true, $country_mapped = false, $city_mapped = false, $with_filter = false, $showCity = false)
    {
        $this->factory = $factory;
        $this->em = $em;
        $this->property_path = $property_path;
        $this->required = $required;
        $this->country_mapped = $country_mapped;
        $this->city_mapped = $city_mapped;
        $this->with_filter = $with_filter;
        $this->showCity = $showCity;
    }

    public static function getSubscribedEvents()
    {
        return [
            FormEvents::PRE_SET_DATA => 'preSetData',
            FormEvents::PRE_SUBMIT => 'preBind',
        ];
    }

    public function preSetData(FormEvent $event)
    {
        $this->addInstitutionFields($event, false);
    }

    private function addInstitutionFields(FormEvent $event, $bind)
    {
        $data = $event->getData();
        $form = $event->getForm();

        $country = null;
        $city = null;
        $institution = null;

        if ($bind && array_key_exists($this->property_path, $data)) {
            $institution = $this->em->getRepository(Institution::class)
                ->find($data[$this->property_path]);
        } else if (is_object($data)) {
            $function = 'get' . ucfirst($this->property_path);
            $institution = $data->$function();
        }

        if ($institution) {
            $city = $institution->getCity();
            if (is_null($city)) {
                $country = $institution->getCountry();
            } else {
                $country = $city->getCountry();
            }
        } else if (is_object($data) && ($data instanceof Institution)) {
            $city = $data->getCity();
            $country = $data->getCountry();
        } else if (is_array($data) && array_key_exists('city', $data) && array_key_exists('country', $data)) {
            $city = $this->em->getRepository(City::class)->find($data['city']);
            $country = $this->em->getRepository(Country::class)->find($data['country']);
        }

        if ($this->with_filter) {
            $filter = null;
            $form->add($this->factory->createNamed('filter', ChoiceType::class, $filter, [
                'choices' => [
                    'liblink' => 'Liblink',
                    'celsius3' => 'Celsius3',
                ],
                'mapped' => false,
                'placeholder' => 'All',
                'required' => false,
                'expanded' => true,
                'attr' => [
                    'class' => 'filter-select',
                ],
                'auto_initialize' => false,
            ]));
        }

        $form->add($this->factory->createNamed('country', EntityType::class, $country, [
            'class' => Country::class,
            'mapped' => $this->country_mapped,
            'placeholder' => '',
            'required' => false,
            'query_builder' => function (CountryRepository $cr) {
                return $cr->getAllOrderedByNameQB();
            },
            'attr' => [
                'class' => 'country-select',
            ],
            'auto_initialize' => false,
            'choice_label' => function ($category) {
                return $this->firstUpper($category);
            }

        ]));

        if ($this->showCity) {
            $form->add($this->factory->createNamed('city', EntityType::class, $city, [
                'class' => City::class,
                'mapped' => $this->city_mapped,
                'placeholder' => '',
                'required' => false,
                'query_builder' => function (CityRepository $cr) use ($country) {
                    return $cr->findForCountryQB($country);
                },
                'attr' => [
                    'class' => 'city-select',
                ],
                'auto_initialize' => false,
                'choice_label' => function ($category) {
                    return $this->firstUpper($category);
                }
            ]));
        }

        $form->add($this->factory->createNamed($this->property_path, EntityType::class, $institution, [
            'class' => Institution::class,
            'property_path' => $this->property_path,
            'label' => ucfirst($this->property_path),
            'placeholder' => '',
            'required' => $this->required,
            'query_builder' => function (InstitutionRepository $ir) use ($city, $country) {
                return $ir->findByCountryAndCityQB($country, $city);
            },
            'attr' => [
                'class' => 'institution-select',
            ],
            'auto_initialize' => false,
        ]));
    }

    private function firstUpper($category)
    {
        if (!is_string($category)) {
            return $category;
        }

        $words = explode(' ', $category);

        $t = '';
        foreach ($words as $k => $word) {
            if (strlen($word) > 3 || $k === 0) {
                $t .= ucfirst(mb_strtolower($word)) . ' ';
            } else {
                $t .= mb_strtolower($word) . ' ';
            }
        }

        return $t;
    }

    public function preBind(FormEvent $event)
    {
        $this->addInstitutionFields($event, true);
    }
}
