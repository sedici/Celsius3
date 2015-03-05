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

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Celsius3\CoreBundle\Entity\Instance;

abstract class BaseController extends Controller
{

    protected function getDirectory()
    {
        return $this->get('celsius3_core.instance_manager')->getDirectory();
    }

    protected function getBundle()
    {
        return 'Celsius3CoreBundle';
    }

    protected function listQuery($name)
    {
        return $this->getDoctrine()->getManager()
                        ->getRepository($this->getBundle() . ':' . $name)
                        ->createQueryBuilder('e');
    }

    protected function findQuery($name, $id)
    {
        return $this->getDoctrine()->getManager()
                        ->getRepository($this->getBundle() . ':' . $name)
                        ->find($id);
    }

    protected function getResultsPerPage()
    {
        return $this->container->getParameter('max_per_page');
    }

    protected function filter($name, $filter_form, $query)
    {
        return $this->get('celsius3_core.filter_manager')->filter($query, $filter_form, 'Celsius3\\CoreBundle\\Entity\\' . $name);
    }

    protected function baseIndex($name, $filter_form = null)
    {
        $query = $this->listQuery($name);
        if (!is_null($filter_form)) {
            $filter_form->bind($this->getRequest());
            $query = $this->filter($name, $filter_form, $query);
        }

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($query, $this->get('request')->query->get('page', 1)/* page number */, $this->getResultsPerPage()/* limit per page */);

        return array(
            'pagination' => $pagination,
            'filter_form' => (!is_null($filter_form)) ? $filter_form->createView() : $filter_form
        );
    }

    protected function baseShow($name, $id)
    {
        $entity = $this->findQuery($name, $id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ' . $name . '.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView()
        );
    }

    protected function baseNew($name, $entity, $type)
    {
        $form = $this->createForm($type, $entity);

        return array(
            'entity' => $entity,
            'form' => $form->createView()
        );
    }

    protected function persistEntity($entity)
    {
        $em = $this->getDoctrine()->getManager();
        $em->persist($entity);
        $em->flush();
    }

    protected function baseCreate($name, $entity, $type, $route)
    {
        $request = $this->getRequest();
        $form = $this->createForm($type, $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $this->persistEntity($entity);
            $this->get('session')
                    ->getFlashBag()
                    ->add('success', 'The ' . $name . ' was successfully created.');

            return $this->redirect($this->generateUrl($route));
        }

        $this->get('session')
                ->getFlashBag()
                ->add('error', 'There were errors creating the ' . $name . '.');

        return array(
            'entity' => $entity,
            'form' => $form->createView()
        );
    }

    protected function baseEdit($name, $id, $type, $route = null)
    {
        $entity = $this->findQuery($name, $id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ' . $name . '.');
        }

        $editForm = $this->createForm($type, $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'route' => $route
        );
    }

    protected function baseUpdate($name, $id, $type, $route)
    {
        $entity = $this->findQuery($name, $id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ' . $name . '.');
        }

        $editForm = $this->createForm($type, $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->get('request_stack')->getCurrentRequest();

        $editForm->bind($request);

        if ($editForm->isValid()) {
            $this->persistEntity($entity);

            $this->get('session')
                    ->getFlashBag()
                    ->add('success', 'The ' . $name . ' was successfully edited.');

            return $this->redirect($this->generateUrl($route . '_edit', array('id' => $id)));
        }

        $this->get('session')->getFlashBag()
                ->add('error', 'There were errors editing the ' . $name . '.');

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView()
        );
    }

    protected function baseDelete($name, $id, $route)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bind($request);

        if ($form->isValid()) {
            $entity = $this->findQuery($name, $id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find ' . $name . '.');
            }

            $this->persistEntity($entity);

            $this->get('session')->getFlashBag()
                    ->add('success', 'The ' . $name . ' was successfully deleted.');
        }

        return $this->redirect($this->generateUrl($route));
    }

    protected function baseBatch()
    {
        $action = $this->getRequest()->request->get('action');
        $function = 'batch' . ucfirst($action);
        $element_ids = $this->getRequest()->request->get('element', array());

        return $this->$function($element_ids);
    }

    protected function baseUnion($name, $ids)
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('Celsius3CoreBundle:' . $name)
                ->createQueryBuilder('e')
                ->where('e.id IN (:ids)')
                ->setParameter('ids', $ids)
                ->getQuery()
                ->getResult();

        return array(
            'entities' => $entities,
        );
    }

    protected function baseDoUnion($name, $ids, $main_id, $route, $updateInstance = true)
    {
        $em = $this->getDoctrine()->getManager();

        $main = $em->getRepository('Celsius3CoreBundle:' . $name)->find($main_id);

        if (!$main) {
            throw $this->createNotFoundException('Unable to find ' . $name . '.');
        }

        $entities = $em->getRepository('Celsius3CoreBundle:' . $name)
                ->createQueryBuilder('e')
                ->where('e.id IN (:ids)')
                ->andWhere('e.id <> :id')
                ->setParameter('ids', $ids)
                ->setParameter('id', $main->getId())
                ->getQuery()
                ->getResult();

        if (count($entities) !== count($ids) - 1) {
            throw $this->createNotFoundException('Unable to find ' . $name . '.');
        }

        $this->get('celsius3_core.union_manager')->union($this->getBundle() . ':' . $name, $main, $entities, $updateInstance);

        $this->get('session')->getFlashBag()->add('success', 'The elements were successfully joined.');

        return $this->redirect($this->generateUrl($route));
    }

    protected function createDeleteForm($id)
    {
        return $this->createFormBuilder(array(
                            'id' => $id,
                        ))
                        ->add('id', 'hidden')
                        ->getForm();
    }

    protected function addFlash($type, $message)
    {
        $this->get('session')->getFlashBag()->add($type, $message);
    }

    protected function ajax(Request $request, Instance $instance = null, $librarian = null)
    {
        if (!$request->isXmlHttpRequest()) {
            return $this->createNotFoundException();
        }

        $target = $request->query->get('target');
        if (!$this->validateAjax($target)) {
            return $this->createNotFoundException();
        }
        
        $term = $request->query->get('term');

        $result = $this->getDoctrine()->getManager()
                ->getRepository('Celsius3CoreBundle:' . $target)
                ->findByTerm($term, $instance, null, $this->get('celsius3_core.user_manager')->getLibrarianInstitutions($librarian))
                ->getResult();

        $json = array();
        foreach ($result as $element) {
            $json[] = array(
                'id' => $element->getId(),
                'value' => $element->__toString()
            );
        }

        $response = new Response(json_encode($json));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
    
    protected function validateAjax($target) {
        return false;
    }
}
