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

namespace Celsius3\Controller\Superadmin\Dashboard;

use Celsius3\Controller\BaseController;
use Celsius3\Entity\BaseUser;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Translation\Translator;

final class SendMessageToAdminsController extends BaseController
{
    public function __invoke(Request $request): RedirectResponse
    {
        $subject = $request->request->get('subject');
        $content = $request->request->get('message');

        if (!$content || empty($content)) {
            throw new NotFoundHttpException();
        }

        $composer = $this->get('fos_message.composer');

        $user = $this->getUser();
        $admins = new ArrayCollection($this->getDoctrine()
            ->getRepository(BaseUser::class)
            ->findAllAdmins());

        $message = $composer->newThread()
            ->setSender($user)
            ->addRecipients($admins)
            ->setSubject($subject)
            ->setBody($content)
            ->getMessage();

        $sender = $this->get('fos_message.sender');

        $sender->send($message);

        /** @var $translator Translator */
        $translator = $this->get('translator');

        $this->addFlash('success', $translator->trans('The message was sent', [], 'Flashes'));

        return $this->redirectToRoute('superadministration');
    }
}
