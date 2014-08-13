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

use Celsius3\CoreBundle\Document\BaseUser;

abstract class BaseUserController extends BaseInstanceDependentController
{

    protected function enableUser(BaseUser $user)
    {
        $user->setEnabled(true);
        $dm = $this->getDocumentManager();
        $dm->persist($user);
        $dm->flush();
    }

    protected function baseTransformAction($id, $transformType)
    {
        $document = $this->findQuery('BaseUser', $id);

        if (!$document) {
            throw $this->createNotFoundException('Unable to find User.');
        }

        $transformForm = $this->createForm($transformType, array(
            'type' => $this->get('celsius3_core.user_manager')->getCurrentRole($document),
            'instances' => $document->getAdministeredInstances(),
        ));

        return array(
            'document' => $document,
            'transform_form' => $transformForm->createView(),
            'route' => null
        );
    }

    protected function baseDoTransformAction($id, $transformType, $route)
    {
        $document = $this->findQuery('BaseUser', $id);

        if (!$document) {
            throw $this->createNotFoundException('Unable to find User.');
        }

        $transformForm = $this->createForm($transformType);

        $request = $this->getRequest();

        $transformForm->bind($request);

        if ($transformForm->isValid()) {
            $data = $transformForm->getData();
            $this->get('celsius3_core.user_manager')->transform($data['type'], $document);

            if (array_key_exists('instances', $data)) {
                $document->getAdministeredInstances()->clear();
                foreach ($data['instances'] as $instance) {
                    $document->addAdministeredInstance($instance);
                }
            }

            $dm = $this->getDocumentManager();
            $dm->persist($document);
            $dm->flush();

            $this->get('session')
                    ->getFlashBag()
                    ->add('success', 'The User was successfully transformed.');

            return $this->redirect($this->generateUrl($route . '_transform', array(
                                'id' => $id
            )));
        }

        $this->get('session')
                ->getFlashBag()
                ->add('error', 'There were errors transforming the User.');

        return array(
            'document' => $document,
            'edit_form' => $transformForm->createView()
        );
    }

    protected function baseEnableAction($id)
    {
        $document = $this->findQuery('BaseUser', $id);

        if (!$document) {
            throw $this->createNotFoundException('Unable to find User.');
        }

        $this->enableUser($document);

        return $this->redirect($this->get('request')->headers->get('referer'));
    }

    protected function baseBatchEnable($element_ids)
    {
        $dm = $this->getDocumentManager();
        $users = $dm->getRepository('Celsius3CoreBundle:BaseUser')
                ->createQueryBuilder()
                ->field('id')
                ->in($element_ids)
                ->getQuery()
                ->execute();

        foreach ($users as $user) {
            $this->enableUser($user);
        }

        return $this->redirect($this->get('request')->headers->get('referer'));
    }

}