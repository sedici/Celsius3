<?php

namespace Celsius\Celsius3Bundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

abstract class BaseController extends Controller
{

    protected function listQuery($name)
    {
        return $this->getDocumentManager()
                        ->getRepository('CelsiusCelsius3Bundle:' . $name)
                        ->createQueryBuilder();
    }

    protected function findQuery($name, $id)
    {
        return $this->getDocumentManager()->getRepository('CelsiusCelsius3Bundle:' . $name)
                        ->find($id);
    }

    protected function getResultsPerPage()
    {
        return $this->container->getParameter('max_per_page');
    }

    protected function baseIndex($name, $filter_form = null)
    {
        $query = $this->listQuery($name);

        if (!is_null($filter_form))
        {
            $filter_form->bind($this->getRequest());
            $query = $this->filter($query, $filter_form, 'Celsius\\Celsius3Bundle\\Document\\' . $name);
        }

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
                $query, $this->get('request')->query->get('page', 1)/* page number */, $this->getResultsPerPage()/* limit per page */
        );

//        $this->get('session')->getFlashBag()->add('warning', 'Your config file is writable, it should be set read-only');
//        $this->get('session')->getFlashBag()->add('error', 'Your config file is writable, it should be set read-only');
//        $this->get('session')->getFlashBag()->add('notice', 'Your config file is writable, it should be set read-only');
//        $this->get('session')->getFlashBag()->add('success', 'Your config file is writable, it should be set read-only');

        return array(
            'pagination' => $pagination,
            'filter_form' => (!is_null($filter_form)) ? $filter_form->createView() : $filter_form,
        );
    }

    protected function baseShow($name, $id)
    {
        $document = $this->findQuery($name, $id);

        if (!$document)
        {
            throw $this->createNotFoundException('Unable to find ' . $name . '.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'document' => $document,
            'delete_form' => $deleteForm->createView(),
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

    public function baseCreate($name, $document, $type, $route)
    {
        $request = $this->getRequest();
        $form = $this->createForm($type, $document);
        $form->bind($request);

        if ($form->isValid())
        {
            $dm = $this->getDocumentManager();
            $dm->persist($document);
            $dm->flush();

            $this->get('session')->getFlashBag()->add('success', 'The ' . $name . ' was successfully created.');

            return $this->redirect($this->generateUrl($route, array('id' => $document->getId())));
        }

        $this->get('session')->getFlashBag()->add('error', 'There were errors creating the ' . $name . '.');

        return array(
            'document' => $document,
            'form' => $form->createView()
        );
    }

    public function baseEdit($name, $id, $type)
    {
        $document = $this->findQuery($name, $id);

        if (!$document)
        {
            throw $this->createNotFoundException('Unable to find ' . $name . '.');
        }

        $editForm = $this->createForm($type, $document);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'document' => $document,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    public function baseUpdate($name, $id, $type, $route)
    {
        $document = $this->findQuery($name, $id);

        if (!$document)
        {
            throw $this->createNotFoundException('Unable to find ' . $name . '.');
        }

        $editForm = $this->createForm($type, $document);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bind($request);

        if ($editForm->isValid())
        {
            $dm = $this->getDocumentManager();
            $dm->persist($document);
            $dm->flush();

            $this->get('session')->getFlashBag()->add('success', 'The ' . $name . ' was successfully edited.');

            return $this->redirect($this->generateUrl($route . '_edit', array('id' => $id)));
        }

        $this->get('session')->getFlashBag()->add('error', 'There were errors editing the ' . $name . '.');

        return array(
            'document' => $document,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    public function baseDelete($name, $id, $route)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bind($request);

        if ($form->isValid())
        {
            $document = $this->findQuery($name, $id);

            if (!$document)
            {
                throw $this->createNotFoundException('Unable to find ' . $name . '.');
            }

            $dm = $this->getDocumentManager();
            $dm->remove($document);
            $dm->flush();

            $this->get('session')->getFlashBag()->add('success', 'The ' . $name . ' was successfully deleted.');
        }

        return $this->redirect($this->generateUrl($route));
    }

    protected function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
                        ->add('id', 'hidden')
                        ->getForm()
        ;
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

    private function filter($query, $form, $class)
    {
        $guesser = $this->get('field.guesser');

        foreach ($form->getData() as $key => $data)
        {
            if (!is_null($data))
            {
                switch ($guesser->getDbType($class, $key))
                {
                    case 'string':
                        $query = $query->field($key)->equals(new \MongoRegex('/.*' . $data . '.*/i'));
                        break;
                    case 'boolean':
                        if ("" !== $value)
                        {
                            $query = $query->field($key)->equals((boolean) $data);
                        }
                        break;
                    case 'int':
                        $query = $query->field($key)->equals((int) $data);
                        break;
                    case 'document':
                    case 'collection':
                        $query = $query->field($key . '.id')->equals(new \MongoId($data->getId()));
                        break;
                    default:
                        $query = $query->field($key)->equals($data);
                        break;
                }
            }
        }

        return $query;
    }

    protected function ajax($instance = null, $librarian = null)
    {
        if (!$this->getRequest()->isXmlHttpRequest())
            return $this->createNotFoundException();

        $target = $this->getRequest()->query->get('target');
        $term = $this->getRequest()->query->get('term');

        $result = $this->getDocumentManager()
                ->getRepository('CelsiusCelsius3Bundle:' . $target)
                ->findByTerm($term, $instance, array(), null, $librarian)
                ->execute();

        $json = array();
        foreach ($result as $element)
        {
            $json[] = array(
                'id' => $element->getId(),
                'value' => $element->__toString(),
            );
        }

        $response = new Response(json_encode($json));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

}
