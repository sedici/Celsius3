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

declare(strict_types=1);

namespace Celsius3\MessageBundle\Twig;

use FOS\MessageBundle\FormFactory\ReplyMessageFormFactory;
use FOS\MessageBundle\Model\ThreadInterface;
use FOS\MessageBundle\Security\ParticipantProvider;
use Symfony\Component\Form\FormView;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class ThreadExtension extends AbstractExtension
{
    private $factory;
    private $participantProvider;

    public function __construct(ReplyMessageFormFactory $factory, ParticipantProvider $participantProvider)
    {
        $this->factory = $factory;
        $this->participantProvider = $participantProvider;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('form_to_thread', [$this, 'formToThread']),
            new TwigFunction('get_unread_messages', [$this, 'getUnreadMessages']),
        ];
    }

    public function formToThread(ThreadInterface $thread): FormView
    {
        return $this->factory->create($thread)->createView();
    }

    public function getUnreadMessages(ThreadInterface $thread): int
    {
        $count = 0;
        foreach ($thread->getMessages() as $message) {
            if (!$message->isReadByParticipant($this->participantProvider->getAuthenticatedParticipant())) {
                ++$count;
            }
        }

        return $count;
    }

    public function getName(): string
    {
        return 'celsius3_message.thread_extension';
    }
}
