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

namespace Celsius3\Controller;

use Celsius3\CoreBundle\Entity\BaseUser;
use Celsius3\CoreBundle\Entity\Instance;
use Celsius3\Exception\Exception;
use Celsius3\Form\Type\BaseUserType;

abstract class BaseUserController extends BaseInstanceDependentController
{
    protected function baseTransform($id, $transformType, array $options = [])
    {
        $entity = $this->findQuery('BaseUser', $id);

        if (!$entity) {
            throw Exception::create(Exception::ENTITY_NOT_FOUND, 'exception.entity_not_found.user');
        }

        if (!$this->getUser()->hasHigherRolesThan($entity)) {
            return $this->redirectToRoute($this->getUserListRoute());
        }

        $transformForm = $this->createForm($transformType, null, $options);

        return [
            'entity' => $entity,
            'transform_form' => $transformForm->createView(),
            'route' => null,
        ];
    }

    protected function baseDoTransform($id, $transformType, array $options, $route)
    {
        $entity = $this->findQuery('BaseUser', $id);

        if (!$entity) {
            throw Exception::create(Exception::ENTITY_NOT_FOUND, 'exception.entity_not_found.user');
        }

        $transformForm = $this->createForm($transformType, null, $options);

        $request = $this->get('request_stack')->getCurrentRequest();

        $transformForm->handleRequest($request);

        if ($transformForm->isValid()) {
            $data = $transformForm->getData();
            $this->get('celsius3_core.user_manager')->transform($data[$entity->getInstance()->getUrl()], $entity);

            foreach ($entity->getSecondaryInstances() as $key => $value) {
                $instance = $this->getDoctrine()->getManager()->getRepository(Instance::class)->find($key);
                if (array_key_exists($instance->getUrl(), $data)) {
                    $entity->addSecondaryInstance($instance, $data[$instance->getUrl()]);
                }
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', 'The User was successfully transformed.');

            return $this->redirect($this->generateUrl($route . '_transform', ['id' => $id]));
        }

        $this->get('session')->getFlashBag()
            ->add('error', 'There were errors transforming the User.');

        return [
            'entity' => $entity,
            'edit_form' => $transformForm->createView(),
        ];
    }

    protected function baseEnable($id)
    {
        $entity = $this->findQuery('BaseUser', $id);

        if (!$entity) {
            throw Exception::create(Exception::ENTITY_NOT_FOUND, 'exception.entity_not_found.user');
        }

        $this->enableUser($entity);

        return $this->redirect($this->get('request_stack')->getCurrentRequest()->headers->get('referer'));
    }

    protected function enableUser(BaseUser $user)
    {
        $user->setEnabled(true);
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();
    }

    protected function baseBatchEnable($element_ids)
    {
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository(BaseUser::class)->findUsers($element_ids);

        foreach ($users as $user) {
            $this->enableUser($user);
        }

        return $this->redirect($this->get('request_stack')->getCurrentRequest()->headers->get('referer'));
    }

    protected function mergeSecondaryInstances(BaseUser $main, array $entities)
    {
        foreach ($entities as $entity) {
            if ($main->getInstance() === $entity->getInstance()) {
                $main->setRoles(array_unique(array_merge($main->getRoles(), $entity->getRoles())));
            } else if ($main->hasSecondaryInstance($entity->getInstance())) {
                $main->addSecondaryInstance($entity->getInstance(), array_unique(array_merge($main->getSecondaryInstances()[$entity->getId()]['roles'], $entity->getRoles())));
            } else {
                $main->addSecondaryInstance($entity->getInstance(), $entity->getRoles());
            }

            foreach ($entity->getSecondaryInstances() as $id => $secondaryInstance) {
                $instance = $this->getDoctrine()->getManager()->getRepository(Instance::class)->find($id);

                if ($main->getInstance() === $instance) {
                    $main->setRoles(array_unique(array_merge($main->getRoles(), $secondaryInstance['roles'])));
                } else if ($main->hasSecondaryInstance($instance)) {
                    $main->addSecondaryInstance($instance, array_unique(array_merge($main->getSecondaryInstances()[(int)$instance->getId()]['roles'], $secondaryInstance['roles'])));
                } else {
                    $main->addSecondaryInstance($instance, $secondaryInstance['roles']);
                }
            }
        }

        $this->getDoctrine()->getManager()->persist($main);
        $this->getDoctrine()->getManager()->flush($main);
    }

    protected function getSortDefaults()
    {
        return [
            'defaultSortFieldName' => 'e.surname',
            'defaultSortDirection' => 'asc',
        ];
    }

    protected function baseUserCreate($request, $template, array $options = [])
    {
        $entity = new BaseUser();

        $form = $this->createForm(BaseUserType::class, $entity, $options);

        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $this->get('celsius3_core.custom_field_helper')->processCustomUserFields($this->getInstance(), $form, $entity);

            $this->get('session')
                ->getFlashBag()
                ->add('success', 'The BaseUser was successfully created.');

            return $this->redirect($this->generateUrl('admin_user'));
        }

        $this->get('session')
            ->getFlashBag()
            ->add('error', 'There were errors creating the BaseUser.');

        $parameters = [
            'entity' => $entity,
            'form' => $form->createView(),
        ];

        return $this->render($template, $parameters);
    }
}
