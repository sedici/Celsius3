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
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\FormFactoryInterface;

class AddEnableCatalogFieldSubscriber implements EventSubscriberInterface
{

    private $em;
    private $factory;

    public function __construct(EntityManager $em, FormFactoryInterface $factory)
    {
        $this->em = $em;
        $this->factory = $factory;
    }

    public static function getSubscribedEvents()
    {
        return array(
            FormEvents::PRE_SET_DATA => 'preSetData',
            FormEvents::PRE_SUBMIT => 'preSubmit',
        );
    }

    public function preSetData(FormEvent $event)
    {
        $form = $event->getForm();
        $data = $event->getData();

        $catalogPosition = $this->em->getRepository('Celsius3CoreBundle:CatalogPosition')
                ->findOneBy(array('catalog' => $data->getId(), 'instance' => $data->getInstance())
        );

        $form->add($this->factory->createNamed('enable', CheckboxType::class, null, array(
                    'mapped' => false,
                    'label' => 'enable',
                    'required' => false,
                    'attr' => array(
                        'checked' => ($catalogPosition) ? $catalogPosition->getEnabled() : false
                    ),
                    'auto_initialize' => false))
        );

        $form->add($this->factory->createNamed('id', HiddenType::class, null, array(
                    'mapped' => false,
                    'data' => $data->getId(),
                    'auto_initialize' => false))
        );
    }

    public function preSubmit(FormEvent $event)
    {
        $form = $event->getForm();
        $data = $event->getData();

        $catalog = $this->em->getRepository('Celsius3CoreBundle:Catalog')->find($data['id']);

        if (!is_null($catalog)) {
            $catalogPosition = $this->em->getRepository('Celsius3CoreBundle:CatalogPosition')
                    ->findOneBy(array('catalog' => $catalog->getId(), 'instance' => $data['instance'])
            );

            if (isset($data['enable'])) {
                $catalogPosition->setEnabled($data['enable']);
            } else {
                $catalogPosition->setEnabled(false);
            }

            $this->em->persist($catalogPosition);
            $this->em->flush();

            $form->add($this->factory->createNamed('enable', CheckboxType::class, null, array(
                        'mapped' => false,
                        'label' => 'enable',
                        'required' => false,
                        'attr' => array(
                            'checked' => $catalogPosition->getEnabled()
                        ),
                        'auto_initialize' => false))
            );
        }
    }

}
