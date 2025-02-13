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

namespace Celsius3\Controller\Html;

use Celsius3\Entity\Order;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Celsius3\Controller\Base\DashboardController;
use Doctrine\Common\Collections\ArrayCollection;


/**
 * SuperAdminDashboard controller.
 * @Route("/superadmin")
 */
class HtmlSuperadminDashboardController extends DashboardController
{

    public function initialize(): void
    {
        parent::initialize();
        $this->htmlRenderer->setTemplatePrefix('Superadmin/Dashboard/');
    }


    /**
     * Lists all items to manage.
     * @Route("/", name="superadministration")
     */
    public function htmlIndex(): Response
    { return $this->htmlRenderer->render('index', $this->index()); }


    /**
     * Lists all Order entities.
     * @Route("/admins_message", name="superadmin_admins_message", methods={"POST", "GET"})
     */
    public function adminsMessage(): RedirectResponse
    {
        $request = $this->requestStack->getCurrentRequest();

        $subject = $request->get('subject');
        $content = $request->get('message');

        if (!$content || empty($content)) {
            throw new NotFoundHttpException();
        }

        $composer = $this->get('fos_message.composer');

        $user = $this->getUser();
        $admins = new ArrayCollection(
            $this->repository
                ->getRepository(Order::class)
                ->findAllAdmins()
        );

        $message = $composer->newThread()
            ->setSender($user)
            ->addRecipients($admins)
            ->setSubject($subject)
            ->setBody($content)
            ->getMessage();

        $sender = $this->get('fos_message.sender');

        $sender->send($message);

        $this->addFlash(
            'success',
            $this->translator->trans(
                'The message was sent',
                [],
                'Flashes'
            )
        );

        return $this->redirectToRoute('superadministration');
    }
}
