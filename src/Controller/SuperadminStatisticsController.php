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

use Celsius3\CoreBundle\Entity\BaseUser;
use Celsius3\CoreBundle\Entity\State;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;


use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Location controller.
 *
 * @Route("/superadmin/statistic")
 */
class SuperadminStatisticsController extends BaseController
{
    /**
     * Lists all Catalog entities.
     *
     * @Route("/", name="superadmin_statistics")
     */
    public function index(Request $request)
    {

        $instance=$this->get('celsius3_core.instance_helper')->getSessionInstance();
        $orderType = null;
        $user=null;
        $orderCount = $this->getDoctrine()->getManager()
            ->getRepository(State::class)
            ->countOrders($instance, $user, $orderType);

        $repository = $this->getDoctrine()->getManager()->getRepository(BaseUser::class);

        $admins = $repository->findManagerOrder($instance);

        return $this->render('Superadmin/Statistics/index.html.twig', array(
            'orderCount' => $orderCount,
            'admin' =>$admins

        ));
    }



    /**
     * GET Route annotation.
     * @POST("/pedidos-por-estado", name="pedidos_por_estados", options={"expose"=true})
     */
    public function getPedidosPorEstado(Request $request)
    {
        $usuario = $request->request->get('user');
        $fecha_desde = $request->request->get('fecha_desde');
        $fecha_hasta = $request->request->get('fecha_hasta');
        $instance=$this->get('celsius3_core.instance_helper')->getSessionInstance();

        $array_json=array();
        if (empty($usuario)){
            $orderType = null;
            $usuario=null;
            $repository = $this->getDoctrine()->getManager()->getRepository(BaseUser::class);
            $managers = $repository->findManagerOrder($instance);
            $array_user=array();
            foreach ($managers as $m){
                $user = $this->getDoctrine()->getManager()->getRepository(BaseUser::class)->find($m);
                $countUserOrders = $this->getDoctrine()->getManager()
                    ->getRepository(State::class)
                    ->countOrdersEntreFechas($instance, $user,null,$fecha_desde,$fecha_hasta);
                $array_user[$user->getId()]['nombre']=$user->getFullName();
                $array_user[$user->getId()]['estados']=$countUserOrders;
            }



        }else{
            $user = $this->getDoctrine()->getManager()->getRepository(BaseUser::class)->find($usuario);
            $countUserOrders = $this->getDoctrine()->getManager()
                ->getRepository(State::class)
                ->countOrdersEntreFechas($instance, $user,null,$fecha_desde,$fecha_hasta);

            $array_user[$user->getId()]['nombre']=$user->getFullName();
            $array_user[$user->getId()]['estados']=$countUserOrders;
        }
        $array_json['result']=$array_user;

        return new JsonResponse($array_json);
    }



    /**
     * GET Route annotation.
     * @POST("/pedidos-por-estado-por-anio", name="pedidos_por_estados_anio", options={"expose"=true})
     */
    public function getPedidosPorEstadoPorAnio(Request $request)
    {
        $usuario = $request->request->get('user');
        $anio_desde = $request->request->get('anio_desde');
        $anio_hasta = $request->request->get('anio_hasta');
        $instance=$this->get('celsius3_core.instance_helper')->getSessionInstance();

        $array_json=array();
        if (empty($usuario)){
            $orderType = null;
            $usuario=null;
            $repository = $this->getDoctrine()->getManager()->getRepository(BaseUser::class);
            $managers = $repository->findManagerOrder($instance);
            $array_user=array();
            foreach ($managers as $m){
                $user = $this->getDoctrine()->getManager()->getRepository(BaseUser::class)->find($m);
                $countUserOrders = $this->getDoctrine()->getManager()
                    ->getRepository(State::class)
                    ->findRequestsStateCountForUser($instance,$anio_desde,$anio_hasta,$user);
                $array_user[$user->getId()]['nombre']=$user->getFullName();
                $array_user[$user->getId()]['estados']=$countUserOrders;
            }



        }else{
            $user = $this->getDoctrine()->getManager()->getRepository(BaseUser::class)->find($usuario);
            $countUserOrders = $this->getDoctrine()->getManager()
                ->getRepository(State::class)
                ->findRequestsStateCountForUser($instance,$anio_desde,$anio_hasta,$user);

            $array_user[$user->getId()]['nombre']=$user->getFullName();
            $array_user[$user->getId()]['estados']=$countUserOrders;
        }
        $array_json['result']=$array_user;

        return new JsonResponse($array_json);
    }



}
