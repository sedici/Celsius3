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

namespace Celsius3\MessageBundle\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Celsius3\MessageBundle\Form\Type\Filter\MessageFilterType;
use FOS\MessageBundle\Controller\MessageController as BaseController;

class MessageController extends BaseController
{
    protected function generateFormsToThreads($threads)
    {
        $forms = array();
        foreach ($threads as $thread) {
            $form = $this->container->get('fos_message.reply_form.factory')
                    ->create($thread);
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
    protected function get($id)
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
        return $this->get('filter_manager')->filter($query, $filter_form, 'Celsius3\\CoreBundle\\Entity\\'.$name);
    }

    protected function getRequest()
    {
        return $this->container->get('request_stack')->getCurrentRequest();
    }

    protected function getResultsPerPage()
    {
        return $this->container->getParameter('max_per_page');
    }

    protected function getSortDefaults()
    {
        return array(
            'wrap-queries' => false,
        );
    }

    /**
     * Displays the authenticated participant inbox.
     */
    public function inboxAction()
    {
        $threads = $this->getProvider()->getInboxThreadsQuery();

        $filter_form = $this->container->get('form.factory')->create(MessageFilterType::class);

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($threads, $this->get('request_stack')->getCurrentRequest()->query->get('page', 1)/* page number */, $this->getResultsPerPage()/* limit per page */, $this->getSortDefaults());

        return $this->container->get('templating')->renderResponse('bundles/FOSMessageBundle/Message/inbox.html.twig', array(
                    'threads' => $pagination,
                    'filter_form' => $filter_form->createView(),
        ));
    }

    /**
     * Displays the authenticated participant sent mails.
     */
    public function sentAction()
    {
        $threads = $this->getProvider()->getSentThreadsQuery();

        $filter_form = $this->container->get('form.factory')->create(MessageFilterType::class);

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($threads, $this->get('request_stack')->getCurrentRequest()->query->get('page', 1)/* page number */, $this->getResultsPerPage()/* limit per page */);

        return $this->container->get('templating')->renderResponse('bundles/FOSMessageBundle/Message/sent.html.twig', array(
                    'threads' => $pagination,
                    'filter_form' => $filter_form->createView(),
        ));
    }

    /**
     * Displays the authenticated participant deleted threads.
     *
     * @return Response
     */
    public function deletedAction()
    {
        $threads = $this->getProvider()->getDeletedThreads();

        $filter_form = $this->container->get('form.factory')->create(MessageFilterType::class);

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($threads, $this->get('request_stack')->getCurrentRequest()->query->get('page', 1)/* page number */, $this->getResultsPerPage()/* limit per page */);

        return $this->container->get('templating')->renderResponse('bundles/FOSMessageBundle/Message/deleted.html.twig', array(
            'threads' => $pagination,
            'filter_form' => $filter_form->createView(),
        ));
    }

    /**
     * Create a new message thread.
     */
    public function newThreadAction()
    {
        $form = $this->container->get('celsius3_message.new_thread_form.factory')->create();
        $formHandler = $this->container->get('fos_message.new_thread_form.handler');

        if ($message = $formHandler->process($form)) {
            return new RedirectResponse($this->container->get('router')->generate('fos_message_thread_view', array(
                        'threadId' => $message->getThread()->getId(),
            )));
        }

        return $this->container->get('templating')
                        ->renderResponse('bundles/FOSMessageBundle/Message/newThread.html.twig', array(
                            'form' => $form->createView(),
                            'data' => $form->getData(),
        ));
    }

    /**
     * Searches for messages in the inbox and sentbox.
     */
    public function searchAction()
    {
        $query = $this->container->get('fos_message.search_query_factory')->createFromRequest();
        $threads = $this->get('doctrine.orm.entity_manager')
                ->getRepository('Celsius3MessageBundle:Thread')
                ->applyExtraFilters($this->container->get('fos_message.search_finder')->getQueryBuilder($query), $this->get('request_stack')->getCurrentRequest(), $this->get('security.token_storage')->getToken()->getUser());

        $filter_form = $this->container->get('form.factory')->create(new MessageFilterType());
        $filter_form->handleRequest($this->get('request_stack')->getCurrentRequest());

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($threads, $this->get('request_stack')->getCurrentRequest()->query->get('page', 1)/* page number */, $this->getResultsPerPage()/* limit per page */);

        return $this->container->get('templating')->renderResponse('bundles/FOSMessageBundle/Message/search.html.twig', array(
                    'query' => $query,
                    'threads' => $pagination,
                    'filter_form' => $filter_form->createView(),
        ));
    }

    public function markAsReadAction(Request $request)
    {
        $threadManager = $this->get('fos_message.thread_manager');
        $participantProvider = $this->get('fos_message.participant_provider');

        $participant = $participantProvider->getAuthenticatedParticipant();

        $threads = $request->request->get('threads', []);

        foreach ($threads as $threadId) {
            $threadManager->markAsReadByParticipant($threadManager->findThreadById($threadId), $participant);
        }

        return new RedirectResponse($this->container->get('router')->generate('fos_message_inbox'));
    }

    /**
     * Displays a thread, also allows to reply to it.
     *
     * @param string $threadId the thread id
     *
     * @return Response
     */
    public function threadAction($threadId)
    {
        $thread = $this->getProvider()->getThread($threadId);
        $form = $this->container->get('celsius3_message.reply_form.factory')->create($thread);
        $formHandler = $this->container->get('fos_message.reply_form.handler');

        if ($message = $formHandler->process($form)) {
            return new RedirectResponse($this->container->get('router')->generate('fos_message_thread_view', array(
                'threadId' => $message->getThread()->getId(),
            )));
        }

        return $this->container->get('templating')->renderResponse('bundles/FOSMessageBundle/Message/thread.html.twig', array(
            'form' => $form->createView(),
            'thread' => $thread,
        ));
    }
}
