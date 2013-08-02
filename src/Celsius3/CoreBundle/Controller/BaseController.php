<?php

namespace Celsius3\CoreBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

abstract class BaseController extends Controller
{

    protected function getBundle()
    {
        return 'Celsius3CoreBundle';
    }

    protected function listQuery($name)
    {
        return $this->getDocumentManager()
                        ->getRepository($this->getBundle() . ':' . $name)
                        ->createQueryBuilder();
    }

    protected function findQuery($name, $id)
    {
        return $this->getDocumentManager()
                        ->getRepository($this->getBundle() . ':' . $name)
                        ->find($id);
    }

    protected function getResultsPerPage()
    {
        return $this->container->getParameter('max_per_page');
    }

    protected function filter($name, $filter_form, $query)
    {
        return $this->get('celsius3_core.filter_manager')->filter($query, $filter_form, 'Celsius3\\CoreBundle\\Document\\' . $name);
    }

    protected function baseIndex($name, $filter_form = null)
    {
        $query = $this->listQuery($name);
        if (!is_null($filter_form)) {
            $filter_form->bind($this->getRequest());
            $query = $this->filter($name, $filter_form, $query);
        }

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($query, $this->get('request')->query->get('page', 1)/* page number */, $this->getResultsPerPage()/* limit per page */
        );

        return array(
            'pagination' => $pagination,
            'filter_form' => (!is_null($filter_form)) ? $filter_form->createView() : $filter_form
        );
    }

    protected function baseShow($name, $id)
    {
        $document = $this->findQuery($name, $id);

        if (!$document) {
            throw $this->createNotFoundException('Unable to find ' . $name . '.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'document' => $document,
            'delete_form' => $deleteForm->createView()
        );
    }

    public function baseNew($name, $document, $type)
    {
        $form = $this->createForm($type, $document);

        return array(
            'document' => $document,
            'form' => $form->createView()
        );
    }

    public function persistDocument($document)
    {
        $dm = $this->getDocumentManager();
        $dm->persist($document);
        $dm->flush();
    }

    public function baseCreate($name, $document, $type, $route)
    {
        $request = $this->getRequest();
        $form = $this->createForm($type, $document);
        $form->bind($request);

        if ($form->isValid()) {
            $this->persistDocument($document);
            $this->get('session')
                    ->getFlashBag()
                    ->add('success', 'The ' . $name . ' was successfully created.');

            return $this->redirect($this->generateUrl($route));
        }

        $this->get('session')
                ->getFlashBag()
                ->add('error', 'There were errors creating the ' . $name . '.');

        return array(
            'document' => $document,
            'form' => $form->createView()
        );
    }

    public function baseEdit($name, $id, $type, $route = null)
    {
        $document = $this->findQuery($name, $id);

        if (!$document) {
            throw $this->createNotFoundException('Unable to find ' . $name . '.');
        }

        $editForm = $this->createForm($type, $document);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'document' => $document,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'route' => $route
        );
    }

    public function baseUpdate($name, $id, $type, $route)
    {
        $document = $this->findQuery($name, $id);

        if (!$document) {
            throw $this->createNotFoundException('Unable to find ' . $name . '.');
        }

        $editForm = $this->createForm($type, $document);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bind($request);

        if ($editForm->isValid()) {
            $dm = $this->getDocumentManager();
            $dm->persist($document);
            $dm->flush();

            $this->get('session')
                    ->getFlashBag()
                    ->add('success', 'The ' . $name . ' was successfully edited.');

            return $this->redirect($this->generateUrl($route . '_edit', array(
                                'id' => $id
            )));
        }

        $this->get('session')
                ->getFlashBag()
                ->add('error', 'There were errors editing the ' . $name . '.');

        return array(
            'document' => $document,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView()
        );
    }

    public function baseDelete($name, $id, $route)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bind($request);

        if ($form->isValid()) {
            $document = $this->findQuery($name, $id);

            if (!$document) {
                throw $this->createNotFoundException('Unable to find ' . $name . '.');
            }

            $dm = $this->getDocumentManager();
            $dm->remove($document);
            $dm->flush();

            $this->get('session')
                    ->getFlashBag()
                    ->add('success', 'The ' . $name . ' was successfully deleted.');
        }

        return $this->redirect($this->generateUrl($route));
    }

    public function baseBatch()
    {
        $action = $this->getRequest()->request->get('action');
        $function = 'batch' . ucfirst($action);
        $element_ids = $this->getRequest()->request->get('element', array());
        return $this->$function($element_ids);
    }

    public function baseUnion($name, $ids)
    {
        $dm = $this->getDocumentManager();
        $documents = $dm->getRepository('Celsius3CoreBundle:' . $name)
                ->createQueryBuilder()
                ->field('id')
                ->in($ids)
                ->getQuery()
                ->execute();

        return array(
            'documents' => $documents
        );
    }

    public function baseDoUnion($name, $ids, $main_id, $route, $updateInstance = true)
    {
        $dm = $this->getDocumentManager();

        $main = $dm->getRepository('Celsius3CoreBundle:' . $name)->find($main_id);

        if (!$main) {
            throw $this->createNotFoundException('Unable to find ' . $name . '.');
        }

        $documents = $dm->getRepository('Celsius3CoreBundle:' . $name)
                ->createQueryBuilder()
                ->field('id')
                ->in($ids)
                ->field('id')
                ->notEqual($main->getId())
                ->getQuery()
                ->execute();

        if ($documents->count() != count($ids) - 1) {
            throw $this->createNotFoundException('Unable to find ' . $name . '.');
        }

        $this->get('celsius3_core.union_manager')->union($name, $main, $documents, $updateInstance);

        $this->get('session')
                ->getFlashBag()
                ->add('success', 'The elements were successfully joined.');

        return $this->redirect($this->generateUrl($route));
    }

    protected function createDeleteForm($id)
    {
        return $this->createFormBuilder(array(
                            'id' => $id
                        ))
                        ->add('id', 'hidden')
                        ->getForm();
    }

    /**
     * Returns the DocumentManager
     *
     * @return DocumentManager
     */
    protected function getDocumentManager()
    {
        return $this->get('doctrine.odm.mongodb.document_manager');
    }

    protected function addFlash($type, $message)
    {
        $this->get('session')->getFlashBag()->add($type, $message);
    }

    protected function ajax($instance = null, $librarian = null)
    {
        if (!$this->getRequest()->isXmlHttpRequest()) {
            return $this->createNotFoundException();
        }

        $target = $this->getRequest()->query->get('target');
        $term = $this->getRequest()->query->get('term');

        $result = $this->getDocumentManager()
                ->getRepository('Celsius3CoreBundle:' . $target)
                ->findByTerm($term, $instance, null, $this->get('celsius3_core.user_manager')->getLibrarianInstitutions($librarian))
                ->execute();

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

}
