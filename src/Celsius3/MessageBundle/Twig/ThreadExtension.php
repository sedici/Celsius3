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

namespace Celsius3\MessageBundle\Twig;
use Symfony\Component\DependencyInjection\ContainerInterface;
use FOS\MessageBundle\Model\ThreadInterface;

class ThreadExtension extends \Twig_Extension
{

    private $container;
    private $environment;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function initRuntime(\Twig_Environment $environment)
    {
        $this->environment = $environment;
    }

    public function getFunctions()
    {
        return array(
                'form_to_thread' => new \Twig_Function_Method($this,
                        'formToThread'),
                'get_unread_messages' => new \Twig_Function_Method($this,
                        'getUnreadMessages'),);
    }

    public function formToThread(ThreadInterface $thread)
    {
        return $this->container->get('fos_message.reply_form.factory')
                ->create($thread)->createView();
    }

    public function getUnreadMessages(ThreadInterface $thread)
    {
        $participantProvider = $this->container
                ->get('fos_message.participant_provider');

        $count = 0;
        foreach ($thread->getMessages() as $message) {
            if (!$message
                    ->isReadByParticipant(
                            $participantProvider->getAuthenticatedParticipant())) {
                $count++;
            }
        }

        return $count;
    }

    public function getName()
    {
        return 'celsius3_message.thread_extension';
    }

}