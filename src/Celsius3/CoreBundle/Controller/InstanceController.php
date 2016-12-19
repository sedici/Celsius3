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

namespace Celsius3\CoreBundle\Controller;

use Celsius3\CoreBundle\Helper\ConfigurationHelper;
use Celsius3\CoreBundle\Validator\Constraints\ContainsCSS;
use Celsius3\CoreBundle\Exception\Exception;
use JMS\TranslationBundle\Annotation\Ignore;

abstract class InstanceController extends BaseController
{
    protected function listQuery($name)
    {
        $qb = parent::listQuery($name)
                ->where('e.id != :id')
                ->setParameter('id', $this->getDirectory()->getId());
        if ($name == 'LegacyInstance') {
            return $qb->andWhere('e INSTANCE OF Celsius3CoreBundle:LegacyInstance');
        } else {
            return $qb;
        }
    }

    /**
     * Construye un array con la configuracion para cada widget .Permite manejar casos especiales como textareas o files.
     *
     * @author gonetil
     *
     * @param $configuration la configuracion del form widget
     * @param $configurationType el tipo widget de configuracion
     *
     * @return array con la estructura esperada por el metodo add de FormBuilder
     */
    private function buildConfigurationArray($configuration, $configurationType)
    {
        $readonly = $configuration->getKey() === ConfigurationHelper::CONF__API_KEY ? 'readonly' : false;
        $config_array = array(
            'constraints' => $this->get('celsius3_core.configuration_helper')->getConstraints($configuration),
            'data' => $this->get('celsius3_core.configuration_helper')->getCastedValue($configuration),
            /** @Ignore */ 'label' => $configuration->getName(),
            'required' => false,
            'attr' => array(
                'value' => $configuration->getValue(),
                'class' => $configurationType === 'Symfony\Component\Form\Extension\Core\Type\TextareaType' && $configuration->getKey() !== ConfigurationHelper::CONF__INSTANCE_CSS ? 'summernote' : '',
                'required' => $configurationType === 'Symfony\Component\Form\Extension\Core\Type\TextareaType' || $configuration->getKey() === ConfigurationHelper::CONF__API_KEY || $configuration->getKey() === ConfigurationHelper::CONF__INSTANCE_LOGO || $configuration->getKey() === ConfigurationHelper::CONF__SHOW_NEWS ? false : true,
                'readonly' => $readonly,
            ),
        );

        if ($configuration->getKey() === ConfigurationHelper::CONF__INSTANCE_CSS) {
            $config_array['constraints'] = new ContainsCSS();
        }

        if ($configuration->getKey() === ConfigurationHelper::CONF__SMTP_PROTOCOL) {
            $config_array['choices'] = ['ssl' => 'SSL', 'tls' => 'TLS'];
        }

        return $config_array;
    }

    private function getConfigurationForm($id, $entity)
    {
        $builder = $this->createFormBuilder();

        foreach ($entity->getConfigurations() as $configuration) {
            $configurationType = $this->get('celsius3_core.configuration_helper')->guessConfigurationType($configuration);
            $builder->add($configuration->getKey(), $configurationType, $this->buildConfigurationArray($configuration, $configurationType));

        }

        return $builder->getForm();
    }

    protected function baseConfigureAction($id)
    {
        $entity = $this->findQuery('Instance', $id);

        if (!$entity) {
            throw Exception::create(Exception::ENTITY_NOT_FOUND, 'exception.entity_not_found.instance');
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
            throw Exception::create(Exception::ENTITY_NOT_FOUND, 'exception.entity_not_found.instance');
        }

        $configureForm = $this->getConfigurationForm($id, $entity);

        $request = $this->get('request_stack')->getCurrentRequest();

        $configureForm->handleRequest($request);

        if ($configureForm->isValid()) {
            $values = $configureForm->getData();
            $class = new \ReflectionClass($this);
            $basedir = dirname($class->getFileName()).'/../../../..';

            $uploadedFile = $configureForm['instance_logo']->getData();
            if (!is_null($uploadedFile)) {
                $randomName = md5(uniqid(mt_rand(), true));
                $uploadedFile->move($basedir.'/web/uploads/logos/', $randomName.'.'.$uploadedFile->guessClientExtension());
                $values['instance_logo'] = $randomName.'.'.$uploadedFile->guessClientExtension();
            }

            $em = $this->getDoctrine()->getManager();

            foreach ($entity->getConfigurations() as $configuration) {
                if ($configuration->getKey() === 'instance_logo' && $configuration->getValue() !== '' && !is_null($configuration->getValue()) && !is_null($values[$configuration->getKey()])) {
                    unlink($basedir.'/web/uploads/logos/'.$configuration->getValue());
                }
                if (!is_null($values[$configuration->getKey()]) || $configuration->getKey() === ConfigurationHelper::CONF__INSTANCE_CSS) {
                    $configuration->setValue($values[$configuration->getKey()]);
                    $em->persist($entity);
                }
            }

            $em->flush();

            $this->addFlash('success', 'The instance was successfully configured.');

            return $this->redirect($this->generateUrl($route.'_configure', array('id' => $id)));
        }

        return array(
            'entity' => $entity,
            'configure_form' => $configureForm->createView(),
        );
    }
}
