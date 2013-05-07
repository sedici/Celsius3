<?php

namespace Celsius\Celsius3Bundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Celsius\Celsius3Bundle\Manager\NotificationManager;

abstract class BaseController extends Controller
{
    /*Given a particular code and a particular idiom, this funtion returns a template*/
    public function getTemplate($code, $idiom)
    {
        $templates = $this->get('doctrine.odm.mongodb.document_manager')
                                      ->getRepository('CelsiusCelsius3Bundle:Template')
                                      ->createQueryBuilder()
                                      ->field('code')->equals($code)
                                      ->field('idiom')->equals($idiom)
                                      ->getQuery()
                                      ->execute()
                                      ->getNext();
        return $templates;
    }
    
    /*This function returns all existing templates for a given idiom*/
    public function getAllTemplate($idiom)
    {
        $templates = $this->get('doctrine.odm.mongodb.document_manager')
                          ->createQueryBuilder('CelsiusCelsius3Bundle:Template')
                          ->field('idiom')->equals($idiom)
                          ->getQuery()
                          ->execute();
        return $templates;
    }
    
    /*Given a cause_notification, this function returns all existing notification for a specific user*/
    public function getNotificationToUser($cause, $user)
    {
        $notifications = $this->get('doctrine.odm.mongodb.document_manager')
                        ->getRepository('CelsiusCelsius3Bundle:Notification')
                        ->createQueryBuilder()
                        ->field('user.$id')->equals(new \MongoId($user->getId()))
                        ->field('cause')->equals($cause)
                        ->field('viewed')->equals(false)
                        ->getQuery()
                        ->execute();
        return $notifications;
    }
    
    /*This function make and returns all messages belonging to the session' user*/
    public function getMessageNotifiaction($cause)
    {
        $notifications = $this->getNotificationToUser($cause, $this->getUser());
        if (count($notifications) > 0) 
        {
            $templateNotification = $this->getTemplate($cause, $this->get('request')->get('_locale'));
            $notificationsMessagesArray = array();
            foreach ($notifications as $notification)
            {
                $env = new \Twig_Environment(new \Twig_Loader_String());
                $renderTemplate = $env->render($templateNotification->getText(),
                                               array("notification" => $notification));
                array_push($notificationsMessagesArray, array ('text' => $renderTemplate,
                                                               'date' => $notification->getCreated()));
            }
            return $notificationsMessagesArray;
        }
    }

    public function loadNotifiactions()
    {
       $causeNotifications = NotificationManager::getCauseNotification();
       foreach($causeNotifications as $cause)
           $array_response[$cause] = $this->getMessageNotifiaction($cause);
       return array('hiddenTemplates' => $array_response);
    }
    
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

    protected function filter($name, $filter_form, $query)
    {
        return $this->get('filter_manager')->filter($query, $filter_form, 'Celsius\\Celsius3Bundle\\Document\\' . $name);
    }

    protected function baseIndex($name, $filter_form = null)
    {
        $query = $this->listQuery($name);
        if (!is_null($filter_form))
        {
            $filter_form->bind($this->getRequest());
            $query = $this->filter($name, $filter_form, $query);
        }

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
                $query, $this->get('request')->query->get('page', 1)/* page number */, $this->getResultsPerPage()/* limit per page */
        );

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

        if ($form->isValid())
        {
            $this->persistDocument($document);
            $this->get('session')->getFlashBag()->add('success', 'The ' . $name . ' was successfully created.');

            return $this->redirect($this->generateUrl($route));
        }

        $this->get('session')->getFlashBag()->add('error', 'There were errors creating the ' . $name . '.');

        return array(
            'document' => $document,
            'form' => $form->createView()
        );
    }

    public function baseEdit($name, $id, $type, $route = null)
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
            'route' => $route
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

    public function baseUnion($name, $ids)
    {
        $dm = $this->getDocumentManager();
        $documents = $dm->getRepository('CelsiusCelsius3Bundle:' . $name)
                ->createQueryBuilder()
                ->field('id')->in($ids)
                ->getQuery()
                ->execute();

        return array(
            'documents' => $documents,
        );
    }

    public function baseDoUnion($name, $ids, $main_id, $route, $updateInstance = true)
    {
        $dm = $this->getDocumentManager();
        
        $main = $dm->getRepository('CelsiusCelsius3Bundle:' . $name)
                ->find($main_id);

        if (!$main)
        {
            throw $this->createNotFoundException('Unable to find ' . $name . '.');
        }

        $documents = $dm->getRepository('CelsiusCelsius3Bundle:' . $name)
                ->createQueryBuilder()
                ->field('id')->in($ids)
                ->field('id')->notEqual($main->getId())
                ->getQuery()
                ->execute();

        if ($documents->count() != count($ids) - 1)
        {
            throw $this->createNotFoundException('Unable to find ' . $name . '.');
        }

        $this->get('union_manager')->union($name, $main, $documents, $updateInstance);

        $this->get('session')->getFlashBag()->add('success', 'The elements were successfully joined.');

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
