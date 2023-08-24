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

use Celsius3\Entity\Country;
use Celsius3\Helper\ConfigurationHelper;
use Celsius3\Helper\InstanceHelper;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * Instance controller.
 *
 * @Route("/admin/instance")
 */
class AdminInstanceController extends InstanceController
{

    /**
     * Displays a form to configure an existing Instance
     *
     * @Route("/configure", name="admin_instance_configure")
     *
     * @param string $id The entity ID
     *
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function configure(ConfigurationHelper  $configurationHelper)
    {
        return $this->render(
            'Admin/Instance/configure.html.twig',
            $this->baseConfigure($this->get('session')->get('instance_id'),$configurationHelper)
        );
    }

    /**
     * Edits the existing Instance configuration.
     *
     * @Route("/{id}/update_configuration", name="admin_instance_update_configuration", methods={"POST"})
     *
     * @param string $id The entity ID
     *
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function configureUpdate($id)
    {
        return $this->render('Admin/Instance/configure.html.twig',$this->baseConfigureUpdate($id, 'admin_instance'));
    }

    /**
     * Edits the existing Instance configuration.
     *
     * @Route("/intercambio", name="admin_instance_intercambio", methods={"GET"})
     *
     * @param string $id The entity ID
     *
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function intercambioUI()
    {
        $instance = $this->get('celsius3_core.instance_helper')->getSessionOrUrlInstance();
        $paisRepository = $this->getDoctrine()->getManager()->getRepository(Country::class);
        $country = $paisRepository->findForInstanceAndGlobal($instance, $this->getDirectory())->getQuery()->execute();

        return $this->render('Admin/Instance/intercambio.html.twig', array(
            'countries' => $country
        ));
    }




}
