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

use Celsius3\CoreBundle\Entity\Instance;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

/**
 * BaseUser controller.
 *
 * @Route("/tichnical")
 */
class TechnicalController extends BaseController
{
    /**
     * Lists all BaseUser entities.
     *
     * @Route("/", name="tichnical_index")
     *
     */
    public function index()
    {
        $instances = $this->getDoctrine()->getManager()
            ->getRepository(Instance::class)
            ->findAllEnabledAndVisible();

        $cInstances = array();
        foreach ($instances as $instance) {
            if (!array_key_exists($instance->getOwnerInstitutions()->first()->getCountry()->getName(), $cInstances)) {
                $cInstances[$instance->getOwnerInstitutions()->first()->getCountry()->getName()] = array();
            }
            $cInstances[$instance->getOwnerInstitutions()->first()->getCountry()->getName()][] = $instance;
        }

        return $this->render('Technical/index.html.twig', array('instances' => $cInstances));
    }

    /**
     *  @Route("/test_smtp", name="technical_instance_rest_test_smtp", options={"expose"=true})
     */
    public function testConnection(Request $request)
    {
        $instance_id = $request->get('instance');
        $instance = $this->getDoctrine()->getManager()
            ->getRepository(Instance::class)
            ->createQueryBuilder('i')
            ->andWhere('i.id = :instance_id')
            ->setParameter('instance_id', $instance_id)
            ->getQuery()
            ->getOneOrNullResult();

        $mailerHelper = $this->get('celsius3_core.mailer_helper');
        $info_connection = $mailerHelper->testConnection(
            $instance->get('smtp_host')->getValue(), $instance->get('smtp_port')->getValue(), $instance->get('smtp_username')->getValue(), $instance->get('smtp_password')->getValue()
        );

        return $this->render('Technical/_testConnection.html.twig', array('info_connection' => $info_connection));
    }
}
