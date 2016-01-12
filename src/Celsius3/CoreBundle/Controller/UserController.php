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
use Symfony\Component\HttpFoundation\Request;

/**
 * User controller
 *
 * @Route("/user")
 */
class UserController extends BaseInstanceDependentController
{

    /**
     * @Route("/", name="user_index")
     * @Template()
     *
     * @return array
     */
    public function indexAction()
    {
        $lastMessages = $this->getDoctrine()->getManager()
                        ->getRepository('Celsius3MessageBundle:Thread')
                        ->createQueryBuilder('t')
                        ->join('t.metadata', 'tm')
                        ->where('tm.participant IN (:participants)')->setParameter('participants', $this->getUser()->getId())
                        ->orderBy('tm.lastMessageDate', 'desc')
                        ->setMaxResults(3)
                        ->getQuery()->getResult();

        $configHelper = $this->get('celsius3_core.configuration_helper');
        $resultsPerPageConfig = $this->getDoctrine()
                ->getManager()
                ->getRepository('Celsius3CoreBundle:Configuration')
                ->findOneBy(
                array(
                    'instance' => $this->getInstance(),
                    'key' => $configHelper::CONF__RESULTS_PER_PAGE));

        return array(
            'lastMessages' => $lastMessages,
            'resultsPerPage' => $resultsPerPageConfig->getValue()
        );
    }

    /**
     * @Route("/ajax", name="user_ajax")
     */
    public function ajaxAction(Request $request)
    {
        return $this->ajax($request, $this->getInstance(), $this->getUser());
    }

    /**
     * @Route("/instance/{id}/change", name="user_change_context")
     */
    public function changeContextAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $instance = $em->getRepository('Celsius3CoreBundle:Instance')->find($id);
        $user = $this->getUser();

        if (array_key_exists($id, $user->getSecondaryInstances()) || ($user->getInstance()->getId() === intval($id))) {

            if (!array_key_exists($user->getInstance()->getId(), $user->getSecondaryInstances())) {
                $user->addSecondaryInstance($user->getInstance(), $user->getRoles());
            }

            if (!$instance || !array_key_exists($id, $user->getSecondaryInstances())) {
                return $this->createNotFoundException('Instance not found');
            }

            $this->get('session')->set('instance_id', $instance->getId());
            $this->get('session')->set('instance_url', $instance->getUrl());
            $this->get('session')->set('instance_host', $instance->getHost());

            $user->setRoles($user->getSecondaryInstances()[$id]['roles']);

            $token = new \Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken(
                    $user, null, 'main', $user->getRoles()
            );
            $this->container->get('security.token_storage')->setToken($token);
        }

        if (isset($user->getRoles()['ROLE_ADMIN'])) {
            return $this->redirect($this->generateUrl('administration'));
        } else {
            return $this->redirect($this->generateUrl('public_index'));
        }
    }

    protected function validateAjax($target)
    {
        $allowedTargets = array(
            'Journal',
            'BaseUser',
        );

        return in_array($target, $allowedTargets);
    }

}
