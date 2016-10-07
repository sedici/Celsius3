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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Celsius3\CoreBundle\Entity\BaseUser;
use Celsius3\CoreBundle\Form\Type\BaseUserType;
use Celsius3\CoreBundle\Form\Type\UserTransformType;
use Celsius3\CoreBundle\Filter\Type\BaseUserFilterType;


use FOS\RestBundle\Controller\Annotations\Post;

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
     * @Template()
     *
     * @return array
     */
    public function indexAction()
    {

        $instances = $this->getDoctrine()->getManager()
            ->getRepository('Celsius3CoreBundle:Instance')
            ->createQueryBuilder('i')
            ->select('o, c, i')
            ->innerJoin('i.ownerInstitutions', 'o')
            ->innerJoin('o.country', 'c')
            ->where('i.enabled = true')
            ->andWhere('i.invisible = :invisible')
            ->setParameter('invisible', false)
            ->getQuery()
            ->getResult();


        $cInstances = array();
        foreach ($instances as $instance) {
            if (!array_key_exists($instance->getOwnerInstitutions()->first()->getCountry()->getName(), $cInstances)) {
                $cInstances[$instance->getOwnerInstitutions()->first()->getCountry()->getName()] = array();
            }
            $cInstances[$instance->getOwnerInstitutions()->first()->getCountry()->getName()][] = $instance;
        }

        return $this->render('Celsius3CoreBundle:Technical:index.html.twig',array('instances' => $cInstances));
    }



    /**
     *  @Route("/test_smtp", name="technical_instance_rest_test_smtp", options={"expose"=true})
     */
    public function testConnectionAction(Request $request)
    {

        $instance_id=$request->get('instance');
        $instance = $this->getDoctrine()->getManager()
            ->getRepository('Celsius3CoreBundle:Instance')
            ->createQueryBuilder('i')
            ->andWhere('i.id = :instance_id')
            ->setParameter('instance_id', $instance_id)
            ->getQuery()
            ->getOneOrNullResult();

        $mailerHelper = $this->get('celsius3_core.mailer_helper');
        $info_connection = $mailerHelper->testConnection(
            $instance, $instance->get('smtp_host')->getValue(), $instance->get('smtp_port')->getValue(), $instance->get('smtp_username')->getValue(),$instance->get('smtp_password')->getValue()
        );

        return $this->render('Celsius3CoreBundle:Technical:_testConnection.html.twig',array('info_connection' => $info_connection));

    }




}
