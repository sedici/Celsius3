<?php

/*
 * Celsius3 - Order management
 * Copyright (C) 2014 PrEBi <info@prebi.unlp.edu.ar>
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

        return array('lastMessages' => $lastMessages);
    }

    /**
     * @Route("/ajax", name="user_ajax")
     */
    public function ajaxAction(Request $request)
    {
        return $this->ajax($request, $this->getInstance(), $this->getUser());
    }
    
    protected function validateAjax($target) {
        $allowedTargets = array(
            'Journal',
        );
        
        return in_array($target, $allowedTargets);
    }
}
