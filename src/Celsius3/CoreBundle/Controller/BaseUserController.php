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

use Celsius3\CoreBundle\Entity\BaseUser;

abstract class BaseUserController extends BaseInstanceDependentController
{

    protected function enableUser(BaseUser $user)
    {
        $user->setEnabled(true);
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();
    }

    protected function baseTransformAction($id, $transformType)
    {
        $entity = $this->findQuery('BaseUser', $id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User.');
        }

        $transformForm = $this->createForm($transformType, array(
            'type' => $this->get('celsius3_core.user_manager')->getCurrentRole($entity),
            'instances' => $entity->getAdministeredInstances(),
        ));

        return array(
            'entity' => $entity,
            'transform_form' => $transformForm->createView(),
            'route' => null
        );
    }

    protected function baseDoTransformAction($id, $transformType, $route)
    {
        $entity = $this->findQuery('BaseUser', $id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User.');
        }

        $transformForm = $this->createForm($transformType);

        $request = $this->getRequest();

        $transformForm->bind($request);

        if ($transformForm->isValid()) {
            $data = $transformForm->getData();
            $this->get('celsius3_core.user_manager')->transform($data['type'], $entity);

            if (array_key_exists('instances', $data)) {
                $entity->getAdministeredInstances()->clear();
                foreach ($data['instances'] as $instance) {
                    $entity->addAdministeredInstance($instance);
                }
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $this->get('session')->getFlashBag()
                    ->add('success', 'The User was successfully transformed.');

            return $this->redirect($this->generateUrl($route . '_transform', array(
                                'id' => $id
            )));
        }

        $this->get('session')->getFlashBag()
                ->add('error', 'There were errors transforming the User.');

        return array(
            'entity' => $entity,
            'edit_form' => $transformForm->createView()
        );
    }

    protected function baseEnableAction($id)
    {
        $entity = $this->findQuery('BaseUser', $id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User.');
        }

        $this->enableUser($entity);

        return $this->redirect($this->get('request')->headers->get('referer'));
    }

    protected function baseBatchEnable($element_ids)
    {
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository('Celsius3CoreBundle:BaseUser')
                ->createQueryBuilder('u')
                ->where('u.id IN (:elements)')
                ->setParameter('elements',$element_ids)
                ->getQuery()
                ->getResutl();

        foreach ($users as $user) {
            $this->enableUser($user);
        }

        return $this->redirect($this->get('request')->headers->get('referer'));
    }

}