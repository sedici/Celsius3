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

use Celsius3\Controller\Base\EmailController;
use Celsius3\Entity\BaseUser;
use Celsius3\Entity\Contact;
use Celsius3\Entity\Order;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;

#[
    Route('/rest/v1/admin/email'),
    IsGranted('ROLE_ADMIN')
]
class RestAdminEmailController extends EmailController
{

    public function initialize(): void
    {
        parent::initialize();
        $this->setInstanceDependent(true);
    }


    #[Route(
        '/',
        name: 'rest_admin_send_email',
        methods: ['POST'],
        options: ['expose' => true]
    )]
    public function restSendEmail(): Response
    {
        $request = $this->requestStack->getCurrentRequest();

        $reqArgs = $request->request->all();

        $urlSubroutes = explode(
            '/', (string) $request->headers->get('referer')
        );
        array_pop($urlSubroutes);
        $receiverClass = array_pop($urlSubroutes);
        if ($receiverClass === 'user') $receiverClass = BaseUser::class;
        else if ($receiverClass === 'contact') $receiverClass = Contact::class;
        else $this->error(
            'not_found', msg: 'Invalid receiver', isRest: true
        );
        
        $params = [];

        $email = $this->checkArg(
            $reqArgs, 'email', 'Email address', isRest: true
        );
        $emailConstraint = new Email();
        $emailConstraint->message = 'Invalid email';
        $errors = $this->validator->validate($email, $emailConstraint);
        if (count($errors) !== 0)
            $this->error('not_found', msg: 'Invalid email address', isRest: true);
        $params['email'] = $email;

        $params['subject'] = $this->checkArg(
            $reqArgs, 'subject', 'Subject', isRest: true
        );

        $params['text'] = $this->checkArg(
            $reqArgs, 'text', 'Email text', isRest: true
        );

        $order_id = (isset($reqArgs['order_id']) && !empty($reqArgs['order_id']))
            ? $reqArgs['order_id'] : null;
        if ($order_id) $params['order'] = $this->entityManager
            ->getRepository(Order::class)
            ->find($order_id);

        // $params['user'] = $this->entityManager
        //     ->getRepository($receiverClass)
        //     ->findOneBy(['email' => $email]);
        
        $params['instance'] = $this->instance;

        $text = $this->emailTeplateController->renderTemplateText(
            $params['text'], $params
        );

        $email = $this->sendEmail(
            $email, $params['subject'], $text
        );

        $statusCode = ($email === null)
            ? Response::HTTP_INTERNAL_SERVER_ERROR
            : Response::HTTP_OK;

        return $this->restRenderer->render(
            $email,
            serializerGroups: 'api_administration',
            statusCode: $statusCode
        );
    }


    #[Route('/', name: 'rest_admin_email', methods: ['GET'], options: ['expose' => true])]
    public function restIndex(): Response
    { return $this->restRenderer->index('api_administration'); }
}
