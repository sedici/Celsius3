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
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

/**
 * Administration controller
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
    
    protected function validateAjax($target) {
        $allowedTargets = array(
            'Journal',
            'BaseUser',
        );
        
        return in_array($target, $allowedTargets);
    }
}
