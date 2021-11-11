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

namespace Celsius3\Controller;

use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Instance controller.
 *
 * @Route("/admin/instance/rest")
 */
class AdminInstanceRestController extends BaseInstanceDependentRestController
{
    /**
     * @Post("/test_smtp", name="admin_instance_rest_test_smtp", options={"expose"=true})
     */
    public function testConnection(Request $request)
    {
        $mailerHelper = $this->get('celsius3_core.mailer_helper');

        $data = $mailerHelper->testConnection(
                $request->request->get('smtp_host'), $request->request->get('smtp_port'), $request->request->get('smtp_protocol'), $request->request->get('smtp_username'), $request->request->get('smtp_password')
        );

        $view = $this->view($data, 200)->setFormat('json');

        return $this->handleView($view);
    }

    /**
     * @Post("/send_email", name="admin_instance_rest_test_send_email", options={"expose"=true})
     */
    public function sendEmail(Request $request)
    {
        $mailerHelper = $this->get('celsius3_core.mailer');
        $translator = $this->get('translator');
        $address = $request->request->get('form_email_to');
        $from = $this->getInstance()->getEmail();
        $subject = $translator->trans('Test');
        $message = 'Celsius3. '.$translator->trans('Test email from')." $from to $address.";

        $data['test'] = $mailerHelper->sendEmail($address, $subject, $message, $this->getInstance());
        $data['message'] = $translator->trans('A test mail was sent to')." $address";
        if (!$data['test']) {
            $data['message'] = $translator->trans('Test mail could not be sent.');
        }

        $view = $this->view($data, 200)->setFormat('json');

        return $this->handleView($view);
    }
}
