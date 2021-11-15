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

use Celsius3\Entity\BaseUser;
use Celsius3\Entity\Order;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Validator\Constraints\Email;
use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\Controller\Annotations\Post;

/**
 * User controller.
 *
 * @Route("/admin/rest/email")
 */
class AdminEmailRestController extends BaseInstanceDependentRestController
{

    /**
     * @Post("", name="admin_rest_email", options={"expose"=true})
     */
    public function sendEmail(Request $request)
    {
        if (!$request->request->has('email')) {
            throw new NotFoundHttpException('Error sending email');
        }
        $email = $request->request->get('email');

        $emailConstraint = new Email();
        $emailConstraint->message = 'Invalid email';

        $errors = $this->get('validator')->validate($email, $emailConstraint);

        if (count($errors) !== 0) {
            throw new NotFoundHttpException('Error sending email');
        }

        if (!$request->request->has('subject')) {
            throw new NotFoundHttpException('Error sending email');
        }
        $subject = $request->request->get('subject');

        if (!$request->request->has('text')) {
            throw new NotFoundHttpException('Error sending email');
        }
        $text = $request->request->get('text');

        $order_id = $request->request->get('order_id');
        $order = ($order_id) ? $this->getDoctrine()->getManager()->getRepository(Order::class)->find($order_id) : null;

        $user = $this->getDoctrine()->getManager()->getRepository(BaseUser::class)->findOneBy(array('email' => $email));

        $mailManager = $this->get('celsius3_core.mail_manager');

        $text = $mailManager->renderRawTemplate($text, array(
            'user' => $user,
            'instance' => $this->getInstance(),
            'order' => $order
        ));

        $result = $this->get('celsius3_core.mailer')->sendEmail($email, $subject, $text, $this->getInstance());

        $view = $this->view($result, 200)->setFormat('json');

        return $this->handleView($view);
    }

}
