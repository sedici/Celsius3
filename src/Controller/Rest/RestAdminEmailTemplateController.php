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

namespace Celsius3\Controller\Rest;

use Celsius3\Controller\Base\EmailTemplateController;
use Celsius3\Entity\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;


#[
    Route('/rest/v1/admin/email_template'),
    IsGranted('ROLE_ADMIN')
]
class RestAdminEmailTemplateController extends EmailTemplateController
{

    #[Route(
        '/',
        name: 'rest_admin_emailtemplate',
        options: ['expose' => true]
    )]
    public function restIndex(): Response
    { return $this->restRenderer->index('api'); }


    #[Route(
        '/{id}',
        name: 'rest_admin_emailtemplate_show',
        options: ['expose' => true]
    )]
    public function getContact(string $id): Response
    { return $this->restRenderer->show($id, 'api'); }


    #[Route(
        '/compiled/{code}/{request_id}',
        name: 'rest_admin_emailtemplate_compiled',
        options: ['expose' => true]
    )]
    public function getCompiledTemplate(
        string $code,
        string $request_id,
        SerializerInterface $serializer
    ): Response {
        $request = $this->entityManager
            ->getRepository(Request::class)
            ->find($request_id);

        if (!$request) $this->error('not_found', entity: Request::class, isRest: true);

        $template = $this->repository
            ->findForInstanceAndGlobal(
                $this->instance, $this->directory, $code
            )
            ->getQuery()
            ->getSingleResult();

        if (!$template) $this->error('not_found');

        $render = $this->renderTemplate(
            $code,
            [
                'user' => $request->getOwner(),
                'instance' => $this->instance,
                'order' => $request->getOrder(),
            ]
        );

        $template->setText($render);

        return $this->restRenderer->render(
            $template, serializerGroups: 'api'
        );

        // $em = $this->entityManager;

        // $request = $em->getRepository(Request::class)
        //         ->find($request_id);

        // if (!$request) {
        //     throw Exception::create(Exception::ENTITY_NOT_FOUND, 'exception.entity_not_found.request');
        // }

        // $template = $em->getRepository(EmailTemplate::class)
        //         ->findGlobalAndForInstance($this->getInstance(), $this->getDirectory(), $code)
        //         ->getQuery()
        //         ->getSingleResult();

        // if (!$template) {
        //     throw Exception::create(Exception::ENTITY_NOT_FOUND, 'exception.entity_not_found.mail_template');
        // }

        // $render = $this->get('celsius3_core.mail_manager')
        //         ->renderTemplate($code, $this->getInstance(), $request->getOwner(), $request->getOrder());

        // $template->setText($render);

        // $view = $this->view(array($template), 200)->setFormat('json');

        // return $this->handleView($view);
    }

}
