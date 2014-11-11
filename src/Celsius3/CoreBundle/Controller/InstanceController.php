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

namespace Celsius3\CoreBundle\Controller;

abstract class InstanceController extends BaseController
{
    protected function listQuery($name)
    {
        $qb = parent::listQuery($name)
                        ->where('id != :id')->setParameter('id',$this->getDirectory()->getId());
        if ($name == 'LegacyInstance') {
            return $qb->andWhere('type = :type')->setParameter('type','legacy');
        } else {
            return $qb;
        }
    }
    
    protected function baseConfigureAction($id)
    {
        $entity = $this->findQuery('Instance', $id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Instance.');
        }

        $configureForm = $this->createFormBuilder();

        foreach ($entity->getConfigurations() as $configuration) {
            $configurationType = $this->get('celsius3_core.configuration_helper')->guessConfigurationType($configuration);
            $configureForm->add($configuration->getKey(), $configurationType, array(
                'data' => $this->get('celsius3_core.configuration_helper')->getCastedValue($configuration),
                /** @Ignore */ 'label' => $configuration->getName(),
                'required' => false,
                'attr' => array(
                    'class' => $configurationType === 'textarea' ? 'summernote' : '',
                    'required' => $configurationType === 'textarea' ? false : true,
                ),
            ));
        }

        return array(
            'entity' => $entity,
            'configure_form' => $configureForm->getForm()->createView(),
        );
    }

    protected function baseConfigureUpdateAction($id, $route)
    {
        $entity = $this->findQuery('Instance', $id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Instance.');
        }

        $builder = $this->createFormBuilder();

        foreach ($entity->getConfigurations() as $configuration) {
            $builder->add($configuration->getKey(), $this->get('celsius3_core.configuration_helper')->guessConfigurationType($configuration), array(
                'attr' => array(
                    'value' => $configuration->getValue(),
                ),
                /** @Ignore */ 'label' => $configuration->getName(),
            ));
        }

        $configureForm = $builder->getForm();

        $request = $this->getRequest();

        $configureForm->bind($request);

        if ($configureForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $values = $configureForm->getData();

            foreach ($entity->getConfigurations() as $configuration) {
                $configuration->setValue($values[$configuration->getKey()]);
                $em->persist($entity);
            }

            $em->flush();

            $this->addFlash('success', 'The instance was successfully configured.');

            return $this->redirect($this->generateUrl($route . '_configure', array('id' => $id)));
        }

        return array(
            'entity' => $entity,
            'configure_form' => $configureForm->createView(),
        );
    }
}