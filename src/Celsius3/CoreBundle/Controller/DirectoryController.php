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

use Celsius3\TicketBundle\Entity\Ticket;
use Celsius3\TicketBundle\Entity\TicketState;
use Celsius3\TicketBundle\Entity\TypeState;
use Celsius3\TicketBundle\Entity\Priority;
use Celsius3\TicketBundle\Entity\Category;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Celsius3\CoreBundle\Entity\Instance;
use Celsius3\CoreBundle\Form\Type\InstanceRegisterType;

/**
 * Directory controller.
 *
 * @Route("/directory/instance")
 */
class DirectoryController extends BaseController
{
    /**
     * @Template()
     *
     * @return array
     */
    public function indexAction()
    {
        return array(
            'directory' => $this->getDirectory(),
            'lastNews' => $this->getDoctrine()->getManager()
                    ->getRepository('Celsius3CoreBundle:News')
                    ->findLastNews($this->getDirectory()),
        );
    }

    /**
     * @Template()
     *
     * @return array
     */
    public function instancesAction(Request $request)
    {
        $instances = $this->getDoctrine()->getManager()
                ->getRepository('Celsius3CoreBundle:Instance')
                ->findAllEnabledAndVisible();

        $legacyInstances = $this->getDoctrine()->getManager()
                ->getRepository('Celsius3CoreBundle:LegacyInstance')
                ->findEnabled();

        $cInstances = array();
        foreach ($instances as $instance) {
            if (!array_key_exists($instance->getOwnerInstitutions()->first()->getCountry()->getName(), $cInstances)) {
                $cInstances[$instance->getOwnerInstitutions()->first()->getCountry()->getName()] = array();
            }
            $cInstances[$instance->getOwnerInstitutions()->first()->getCountry()->getName()][] = $instance;
        }

        $lInstances = array();
        foreach ($legacyInstances as $instance) {
            if (!array_key_exists($instance->getOwnerInstitutions()->first()->getCountry()->getName(), $lInstances)) {
                $lInstances[$instance->getOwnerInstitutions()->first()->getCountry()->getName()] = array();
            }
            $lInstances[$instance->getOwnerInstitutions()->first()->getCountry()->getName()][] = $instance;
        }

        $latitude = '-34.9189929';
        $longitude = '-57.9523734';

        $instancia_mapa = $this->get('celsius3_core.instance_manager')->findInstance($latitude, $longitude);

        $map = $this->get('celsius3_core.map_manager')->createMapFromApiSearch($instancia_mapa, $latitude, $longitude);

        $map->setMapOption('zoom', (int) $request->get('zoom'));

        return array(
            'directory' => $this->getDirectory(),
            'instances' => $cInstances,
            'legacyInstances' => $lInstances,
            'map' => $map,
        );
    }

    /**
     * @Template()
     */
    public function statisticsAction()
    {
        return array(
            'directory' => $this->getDirectory(),
        );
    }

    /**
     * @Route("/instance-register", name="instance_register", options={"expose"=true})
     * @Template()
     */
    public function registerInstanceAction()
    {
        $entity = new Instance();
        $form = $this->createForm(InstanceRegisterType::class, $entity);

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
            'directory' => $this->getDirectory(),
        );
    }

        /**
         * Creates a new Instance entity.
         *
         * @Route("/create-register", name="directory_instance_create")
         * @Method("post")
         * @Template("Celsius3CoreBundle:Directory:registerInstance.html.twig")
         *
         * @return array
         */
        public function createAction(Request $request)
        {
            $paramteros = $request->get('instance_register');

            $entity = new Instance();
            $form = $this->createForm(InstanceRegisterType::class, $entity);
            /*
             * Informacion de la instancia
             * */

            $apellido_nombre = $paramteros['apellido_nombre'];
            $email = $paramteros['email'];

            $country = $paramteros['country'];
            $city = $paramteros['city'];
            $institution = $paramteros['institution'];

            $sitio_biblioteca = $paramteros['sitio_biblioteca'];
            $sitio_institucion = $paramteros['sitio_institucion'];
            $sitio_catalogo = $paramteros['sitio_catalogo'];

            $migrar = $paramteros['migrar'];

            $observaciones = $paramteros['observaciones'];

            $texto = "$apellido_nombre solicito agregar una nueva instancia $country $city $institution con la siguiente informacion: <br/>URL Institucion: $sitio_institucion";
            $texto .= "<br/> URL Biblioteca $sitio_biblioteca <br/> URL Catalogo: $sitio_catalogo";
            $texto .= "<br/> Correo de contacto $email";
            $texto .= "<br/> Observaciones: $observaciones";
            if ($migrar) {
                $texto .= '<br/> Se solicita migración.';
            }

            $parametros=array();
            $parametros['subject']='Nueva Instancia Cargada';
            $parametros['texto']=$texto;
            $parametros['priority']=Priority::PRIORITY_MEDIA;
            $parametros['category']=Category::CATEGORY_NEW_INSTANCE;
            $parametros['typeState']=TypeState::TYPE_STATE_NEW;



            $ticketHelper = $this->get('celsius3_ticket.ticket_helper');
            $ticketHelper->setParametros($parametros);
            $ticketHelper->createTicket();


            return array(
            'entity' => $entity,
            'form' => $form->createView(),
            'directory' => $this->getDirectory(),
        );
        }
}
