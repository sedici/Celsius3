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

namespace Celsius3\Controller\Html;

use Celsius3\Controller\Base\MessageController;
use Celsius3\Entity\BaseUser;
use Celsius3\Form\Type\Filter\MessageFilterType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[
    Route('/user/message'),
    IsGranted('IS_AUTHENTICATED_FULLY')
]
 class HtmlMessageController extends MessageController
{

    #[Route(
        '/',
        name: 'fos_message_inbox',
        methods: ['GET']
    )]
    public function inboxAction(): Response
    {
        // throw new \Exception((string)var_dump());

        // $threads = $this->threadController->getUserThreads(
        //     $this->entityManager->getRepository(BaseUser::class)->find(634)
        // );

        // $threads = $this->getProvider()->getInboxThreadsQuery();

        // throw new \Exception((string)var_dump($this->listQuery()->getQuery()->execute()));


        return $this->htmlRenderer->render(
            'inbox',
            array_merge(
                $this->index(hasFilterForm: false, isInstanceDependent: false),
                [ 'threads' => $this->threadRepository->getParticipantInboxThreadsQueryBuilder(
                    $this->entityManager->getRepository(BaseUser::class)->find(634)
                )->getQuery()->execute() ]
                // getProvider()->getInboxThreadsQuery()
            )
        );

        $filter_form = $this->createForm(MessageFilterType::class);

        $request = $this->requestStack->getCurrentRequest();

        $pagination = $this->paginator->paginate($threads);

        return $this->htmlRenderer->render(
            'inbox',
            [
                'threads' => $pagination,
                'filter_form' => $filter_form->createView(),
            ]
        );
    }


    /**
     * Displays the authenticated participant sent mails.
     * @Route("/sent", name="fos_message_sent")
     */
    public function sentAction(): Response
    {
        $threads = $this->getProvider()->getSentThreadsQuery();

        $filter_form = $this->createForm(MessageFilterType::class);

        $request = $this->requestStack->getCurrentRequest();

        $pagination = $this->paginator->paginate(
            $threads,
            $request->query->get('page', 1),
            $this->getResultsPerPage()
        );

        return $this->htmlRenderer->render(
            'sent',
            [
                'threads' => $pagination,
                'filter_form' => $filter_form->createView(),
            ]
        );
    }


    /**
     * Displays the authenticated participant deleted threads.
     * @Route("/deleted", name="fos_message_deleted")
     */
    public function deletedAction(): Response
    {
        $threads = $this->getProvider()->getDeletedThreads();

        $filter_form = $this->createForm(MessageFilterType::class);
        
        $request = $this->requestStack->getCurrentRequest();

        $pagination = $this->paginator->paginate($threads);

        return $this->htmlRenderer->render(
            'deleted',
            [
                'threads' => $pagination,
                'filter_form' => $filter_form->createView(),
            ]
        );
    }



    /**
     * @Route("/search", name="fos_message_search", methods={"GET"})
     */
    public function searchAction(): Response
    {
        return $this->redirectToRoute('fos_message_inbox');
    }

    /**
     * @Route("/new", name="fos_message_thread_new", methods={"GET"})
     */
    public function newThreadAction(): Response
    {
        return $this->redirectToRoute('fos_message_inbox');
    }

    /**
     * @Route("/mark_read", name="fos_message_mark_read", methods={"POST"})
     */
    public function markAsReadAction(): Response
    {
        return $this->redirectToRoute('fos_message_inbox');
    }

    /**
     * @Route("/{threadId}/delete", name="fos_message_thread_delete", methods={"POST", "DELETE"})
     */
    public function deleteAction(string $threadId): Response
    {
        return $this->redirectToRoute('fos_message_inbox');
    }

    /**
     * @Route("/{threadId}/undelete", name="fos_message_thread_undelete", methods={"POST"})
     */
    public function undeleteAction(string $threadId): Response
    {
        return $this->redirectToRoute('fos_message_inbox');
    }

    /**
     * @Route("/{threadId}", name="fos_message_thread_view", methods={"GET"})
     */
    public function viewThreadAction(string $threadId): Response
    {
        return $this->redirectToRoute('fos_message_inbox');
    }
}
