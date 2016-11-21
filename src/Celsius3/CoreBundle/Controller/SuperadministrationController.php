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

namespace Celsius3\CoreBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Administration controller.
 *
 * @Route("/superadmin")
 */
class SuperadministrationController extends BaseController
{
    /**
     * @Route("/", name="superadministration")
     * @Template()
     *
     * @return array
     */
    public function indexAction()
    {
        return array();
    }




    /**
     * @Route("/administradores", name="superadmin_admin")
     */
    public function administradoresAction(Request $request)
    {
        return $this->ajax($request);
    }



    /**
     * @Route("/ajax", name="superadmin_ajax")
     */
    public function ajaxAction(Request $request)
    {
        return $this->ajax($request);
    }

    /**
     * @Route("/orderusertable", name="superadmin_orderusertable", options={"expose"=true})
     * @Template()
     */
    public function orderUserTableAction(Request $request)
    {
        if (!$request->isXmlHttpRequest()) {
            return $this->createNotFoundException();
        }

        return new Response(json_encode($this->get('celsius3_core.statistic_manager')->getOrderUserTableData()));
    }

    protected function validateAjax($target)
    {
        $allowedTargets = array(
            'Journal',
            'BaseUser',
        );

        return in_array($target, $allowedTargets);
    }

    /**
     * @Route("/software_change_message", name="superadmin_software_update_message", options={"expose"=true})
     */
    public function softwareUpdateMessageAction(Request $request)
    {
        $content = $request->request->get('message');

        if (!$content || empty($content)) {
            throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException();
        }

        $composer = $this->get('fos_message.composer');

        $user = $this->getUser();
        $admins = new ArrayCollection($this->getDoctrine()
                        ->getRepository('Celsius3CoreBundle:BaseUser')
                        ->findAllAdmins());

        $message = $composer->newThread()
                ->setSender($user)
                ->addRecipients($admins)
                ->setSubject('Actualización del software Celsius3')
                ->setBody($content)
                ->getMessage();

        $sender = $this->get('fos_message.sender');

        $sender->send($message);

        $this->addFlash('success', 'The message was sent');

        return $this->redirectToRoute('superadministration');
    }
}
