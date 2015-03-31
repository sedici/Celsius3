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

namespace Celsius3\CoreBundle\Form\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManager;
use Celsius3\CoreBundle\Entity\Country;
use Celsius3\CoreBundle\Entity\City;

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

        // During form creation setData() is called with null as an argument
        // by the FormBuilder constructor. You're only concerned with when
        // setData is called with an actual Entity object in it (whether new
        // or fetched with Doctrine). This if statement lets you skip right
        // over the null condition.
        if (null === $data) {
            return;
        }

        $country = null;
        $city = null;
        $institution = null;

        if ($bind && array_key_exists($this->property_path, $data)) {
            $institution = $this->em->getRepository('Celsius3CoreBundle:Institution')
                    ->find($data[$this->property_path]);
        } elseif (is_object($data)) {
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
        }

        if ($this->with_filter) {
            $filter = null;
            $form->add($this->factory->createNamed('filter', 'choice', $filter, array(
                        'choices' => array(
                            'liblink' => 'Liblink',
                            'celsius3' => 'Celsius3',
                        ),
                        'mapped' => false,
                        'empty_value' => 'All',
                        'required' => false,
                        'expanded' => true,
                        'attr' => array(
                            'class' => 'filter-select'
                        ),
                        'auto_initialize' => false,
            )));
        }

        $form->add($this->factory->createNamed('country', 'entity', $country, array(
                    'class' => 'Celsius3CoreBundle:Country',
                    'mapped' => $this->country_mapped,
                    'placeholder' => '',
                    'required' => false,
                    'query_builder' => function (EntityRepository $er) {
                        return $er
                                        ->createQueryBuilder('c')
                                        ->orderBy('c.name', 'asc');
                    },
                    'attr' => array(
                        'class' => 'country-select'
                    ),
                    'auto_initialize' => false,
        )));

        $form->add($this->factory->createNamed('city', 'entity', $city, array(
                    'class' => 'Celsius3CoreBundle:City',
                    'mapped' => $this->city_mapped,
                    'placeholder' => '',
                    'required' => false,
                    'query_builder' => function (EntityRepository $repository) use ($country) {
                        $qb = $repository->createQueryBuilder('c');

                        if ($country instanceof Country) {
                            $qb = $qb->where('c.country = :country_id')
                                    ->setParameter('country_id', $country->getId());
                        } else {
                            $qb = $qb->where('c.country IS NULL');
                        }

                        return $qb->orderBy('c.name', 'asc');
                    },
                    'attr' => array(
                        'class' => 'city-select'
                    ),
                    'auto_initialize' => false,
        )));

        $form->add($this->factory->createNamed($this->property_path, 'entity', $institution, array(
                    'class' => 'Celsius3CoreBundle:Institution',
                    'property_path' => $this->property_path,
                    'label' => ucfirst($this->property_path),
                    'placeholder' => '',
                    'required' => $this->required,
                    'query_builder' => function (EntityRepository $repository) use ($city, $country) {
                        $qb = $repository->createQueryBuilder('i');

                        if ($city instanceof City) {
                            $qb = $qb->where('i.city = :city_id')
                                    ->setParameter('city_id', $city->getId());
                        } elseif ($country instanceof Country) {
                            $qb = $qb->where('i.country = :country_id')
                                    ->andWhere('i.city IS NULL')
                                    ->setParameter('country_id', $country->getId());
                        } else {
                            $qb = $qb->where('i.city IS NULL')
                                    ->andWhere('i.country IS NULL');
                        }

                        return $qb->orderBy('i.name', 'asc');
                    },
                    'attr' => array(
                        'class' => 'institution-select'
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
}
