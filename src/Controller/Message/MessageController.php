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

namespace Celsius3\Controller\Message;

use Celsius3\Entity\Mixin\ProviderTrait;
use Celsius3\Form\Type\Filter\MessageFilterType;
use Celsius3\Controller\BaseEntityController;
use Celsius3\Entity\Message;
use Symfony\Component\HttpFoundation\Response;

class MessageController extends BaseEntityController
{
    use ProviderTrait;

    protected final function getEntity(): string
    { return Message::class; }

    protected final function getType(): string
    { return ''; }

    protected final function getTemplatePrefix(): string
    { return 'bundles/FOSMessageBundle/Message/'; }

    protected function getSortDefaults(): array
    { return [ 'wrap-queries' => false ]; }


    /**
     * Displays the authenticated participant inbox.
     */
    public function inboxAction(): Response
    {
        $threads = $this->getProvider()->getInboxThreadsQuery();

        $filter_form = $this->createForm(
            MessageFilterType::class, $threads
        );

        $request = $this->requestStack->getCurrentRequest();

        $pagination = $this->paginator->paginate(
            $threads,
            $request->query->get('page', 1),
            $this->getResultsPerPage(),
            $this->getSortDefaults()
        );

        return $this->render(
            (string) $this->templatePrefix . '/inbox.html.twig',
            [
                'threads' => $pagination,
                'filter_form' => $filter_form->createView(),
            ]
        );
    }

    /**
     * Gets a service by id.
     *
     * @param string $id The service id
     *
     * @return object The service
     */
    protected function get(string $id): object
    {
        return $this->container->get($id);
    }


    protected function getResultsPerPage()
    {
        return $this->container->getParameter('max_per_page');
    }


    /**
     * Displays the authenticated participant sent mails.
     */
    public function sentAction(): Response
    {
        $threads = $this->getProvider()->getSentThreadsQuery();

        $filter_form = $this->container->get('form.factory')->create(MessageFilterType::class);

        $request = $this->requestStack->getCurrentRequest();

        $pagination = $this->paginator->paginate(
            $threads,
            $request->query->get('page', 1),
            $this->getResultsPerPage()
        );

        return $this->render(
            (string) $this->templatePrefix . 'sent.html.twig',
            [
                'threads' => $pagination,
                'filter_form' => $filter_form->createView(),
            ]
        );
    }


    /**
     * Displays the authenticated participant deleted threads.
     *
     * @return Response
     */
    public function deletedAction(): Response
    {
        $threads = $this->getProvider()->getDeletedThreads();

        $filter_form = $this->container->get('form.factory')->create(MessageFilterType::class);
        
        $request = $this->requestStack->getCurrentRequest();

        $pagination = $this->paginator->paginate(
            $threads,
            $request->query->get('page', 1),
            $this->getResultsPerPage()
        );

        return $this->render(
            $this->templatePrefix . 'deleted.html.twig',
            [
                'threads' => $pagination,
                'filter_form' => $filter_form->createView(),
            ]
        );
    }
}
