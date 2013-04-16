<?php

namespace Celsius\Celsius3MessageBundle\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Celsius\Celsius3Bundle\Filter\Type\MessageFilterType;
use FOS\MessageBundle\Controller\MessageController as BaseController;

class MessageController extends BaseController
{

    public function generateFormsToThreads($threads)
    {
        $forms = array();
        foreach ($threads as $thread)
        {
            $form = $this->container->get('fos_message.reply_form.factory')->create($thread);
            $forms[$thread->getId()] = $form->createView();
        }
        return $forms;
    }

    /**
     * Gets a service by id.
     *
     * @param string $id The service id
     *
     * @return object The service
     */
    public function get($id)
    {
        return $this->container->get($id);
    }

    /**
     * Shortcut to return the request service.
     *
     * @return Request
     */
    protected function filter($name, $filter_form, $query)
    {
        return $this->get('filter_manager')->filter($query, $filter_form, 'Celsius\\Celsius3Bundle\\Document\\' . $name);
    }

    public function getRequest()
    {
        return $this->container->get('request');
    }

    protected function getResultsPerPage()
    {
        return $this->container->getParameter('max_per_page');
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

    /**
     * Displays the authenticated participant inbox
     */
    public function inboxAction()
    {

        $threads = $this->getProvider()->getInboxThreadsQuery();

//        $filter_form = $this->container->get('form.factory')->create(new MessageFilterType());
//
//        $query = $this->listQuery('Message');
//        if (!is_null($filter_form))
//        {
//            $filter_form->bind($this->getRequest());
//            $query = $this->filter('Message', $filter_form, $query);
//        }

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
                $threads, $this->get('request')->query->get('page', 1)/* page number */, $this->getResultsPerPage()/* limit per page */
        );

        return $this->container->get('templating')->renderResponse('FOSMessageBundle:Message:inbox.html.twig', array(
                    'threads' => $pagination,
        ));
    }

    /**
     * Displays a thread, also allows to reply to it
     */
    public function threadAction($threadId)
    {
        $thread = $this->getProvider()->getThread($threadId);
        $form = $this->container->get('fos_message.reply_form.factory')->create($thread);
        $formHandler = $this->container->get('fos_message.reply_form.handler');

        if ($message = $formHandler->process($form))
        {
            return new RedirectResponse($this->container->get('router')->generate('fos_message_thread_view', array(
                        'threadId' => $message->getThread()->getId()
            )));
        }

        return $this->container->get('templating')->renderResponse('FOSMessageBundle:Message:thread.html.twig', array(
                    'form' => $form->createView(),
                    'thread' => $thread
        ));
    }

    /**
     * Displays the authenticated participant sent mails
     */
    public function sentAction()
    {
        $threads = $this->getProvider()->getSentThreadsQuery();

//        $filter_form = $this->container->get('form.factory')->create(new MessageFilterType());
//
//        $query = $this->listQuery('Message');
//
//        if (!is_null($filter_form))
//        {
//            $filter_form->bind($this->getRequest());
//            $query = $this->filter('Message', $filter_form, $query);
//            //$threads = $this->filter('Message', $filter_form, $threads);
//        }

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
                $threads, $this->get('request')->query->get('page', 1)/* page number */, $this->getResultsPerPage()/* limit per page */
        );

        return $this->container->get('templating')->renderResponse('FOSMessageBundle:Message:sent.html.twig', array(
                    'threads' => $pagination,
        ));
    }

    /**
     * Create a new message thread
     */
    public function newThreadAction()
    {
        $form = $this->container->get('fos_message.new_thread_form.factory')->create();
        $formHandler = $this->container->get('fos_message.new_thread_form.handler');

        if ($message = $formHandler->process($form))
        {
            return new RedirectResponse($this->container->get('router')->generate('fos_message_thread_view', array(
                        'threadId' => $message->getThread()->getId()
            )));
        }

        return $this->container->get('templating')->renderResponse('FOSMessageBundle:Message:newThread.html.twig', array(
                    'form' => $form->createView(),
                    'data' => $form->getData(),
        ));
    }

    /**
     * Deletes a thread
     */
    public function deleteAction($threadId)
    {
        $thread = $this->getProvider()->getThread($threadId);
        $this->container->get('fos_message.deleter')->markAsDeleted($thread);
        $this->container->get('fos_message.thread_manager')->saveThread($thread);

        return new RedirectResponse($this->container->get('router')->generate('admin_messages_inbox'));
    }

    /**
     * Searches for messages in the inbox and sentbox
     */
    public function searchAction()
    {
        $query = $this->container->get('fos_message.search_query_factory')->createFromRequest();
        $threads = $this->container->get('fos_message.search_finder')->find($query);

        return $this->container->get('templating')->renderResponse('FOSMessageBundle:Message:search.html.twig', array(
                    'query' => $query,
                    'threads' => $threads
        ));
    }

}
