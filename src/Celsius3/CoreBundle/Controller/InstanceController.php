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

use Celsius3\CoreBundle\Helper\ConfigurationHelper;
use Symfony\Component\Validator\Constraints as Assert;

abstract class InstanceController extends BaseController
{

    protected function listQuery($name)
    {
        $qb = parent::listQuery($name)
                        ->where('e.id != :id')->setParameter('id', $this->getDirectory()->getId());
        if ($name == 'LegacyInstance') {
            return $qb->andWhere('e INSTANCE OF Celsius3CoreBundle:LegacyInstance');
        } else {
            return $qb;
        }
    }

    private function getConfigurationForm($id, $entity)
    {

        $builder = $this->createFormBuilder();

        foreach ($entity->getConfigurations() as $configuration) {
            $configurationType = $this->get('celsius3_core.configuration_helper')->guessConfigurationType($configuration);
            $readonly = $configuration->getKey() === ConfigurationHelper::CONF__API_KEY ? 'readonly' : false;
            $builder->add($configuration->getKey(), $configurationType, array(
                'constraints' => $this->get('celsius3_core.configuration_helper')->getConstraints($configuration),
                'data' => $this->get('celsius3_core.configuration_helper')->getCastedValue($configuration),
                /** @Ignore */ 'label' => $configuration->getName(),
                'required' => false,
                'attr' => array(
                    'value' => $configuration->getValue(),
                    'class' => $configurationType === 'textarea' ? 'summernote' : '',
                    'required' => $configurationType === 'textarea' || $configuration->getKey() === ConfigurationHelper::CONF__API_KEY || $configuration->getKey() === ConfigurationHelper::CONF__INSTANCE_LOGO ? false : true,
                    'readonly' => $readonly,
                ),
            ));
        }

        return $builder->getForm();
    }

    protected function baseConfigureAction($id)
    {
        $entity = $this->findQuery('Instance', $id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Instance.');
        }

        $configureForm = $this->getConfigurationForm($id, $entity);

        return array(
            'entity' => $entity,
            'configure_form' => $configureForm->createView(),
        );
    }

    protected function baseConfigureUpdateAction($id, $route)
    {
        $entity = $this->findQuery('Instance', $id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Instance.');
        }

        $configureForm = $this->getConfigurationForm($id, $entity);

        $request = $this->get('request_stack')->getCurrentRequest();

        $configureForm->handleRequest($request);

        if ($configureForm->isValid()) {
            $values = $configureForm->getData();

            $uploadedFile = $configureForm['instance_logo']->getData();
            if (!is_null($uploadedFile)) {
                $randomName = md5(uniqid(mt_rand(), true));
                $uploadedFile->move(__DIR__ . '/../../../../web/uploads/logos/', $randomName . '.' . $uploadedFile->guessClientExtension());
                $values['instance_logo'] = $randomName . '.' . $uploadedFile->guessClientExtension();
            }

            $em = $this->getDoctrine()->getManager();

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
