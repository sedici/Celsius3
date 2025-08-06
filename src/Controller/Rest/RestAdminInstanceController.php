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

use Celsius3\Controller\Base\InstanceController;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;

#[
    Route('/rest/v1/admin/instance'),
    IsGranted('ROLE_ADMIN')
]
class RestAdminInstanceController extends InstanceController
{

    #[Post(
        '/test_smtp',
        name: 'admin_instance_rest_test_smtp',
        options: ['expose' => true]
    )]
    public function testConnection(): Response
    {
        $request = $this->requestStack->getCurrentRequest();
        $data = $this->emailController->testConnection(
            $request->request->get('smtp_host'),
            $request->request->get('smtp_port'),
            $request->request->get('smtp_protocol'),
            $request->request->get('smtp_username'),
            $request->request->get('smtp_password')
        );

        return $this->restRenderer->render($data);
    }


    #[Post(
        '/send_email',
        name: 'admin_instance_rest_test_send_email',
        options: ['expose' => true])
    ]
    public function sendEmail(): Response
    {
        $request = $this->requestStack->getCurrentRequest();
        $address = $request->request->get('form_email_to');
        $from = $this->instance->getEmail();
        $subject = $this->translator->trans('Test');
        $message = 'Celsius3. '.$this->translator->trans('Test email from')." $from to $address.";

        $data['test'] = $this->emailController->sendMimeEmail(
            $from, $address, $subject, $message
        );
        $data['message'] = $this->translator->trans('A test mail was sent to')." $address";
        if (!$data['test']) {
            $data['message'] = $this->translator->trans('Test mail could not be sent.');
        }

        return $this->restRenderer->render($data);
    }
}
