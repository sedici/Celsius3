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

use Celsius3\CoreBundle\Entity\Instance;
use Celsius3\CoreBundle\Form\Type\InstanceRegisterType;
use Celsius3\TicketBundle\Entity\Category;
use Celsius3\TicketBundle\Entity\Priority;
use Celsius3\TicketBundle\Entity\TypeState;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

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
        $instancesMarkers = array();
        foreach ($instances as $instance) {
            if (!array_key_exists($instance->getOwnerInstitutions()->first()->getCountry()->getName(), $cInstances)) {
                $cInstances[$instance->getOwnerInstitutions()->first()->getCountry()->getName()] = array();
            }
            $cInstances[$instance->getOwnerInstitutions()->first()->getCountry()->getName()][] = $instance;

            $instanceLatitude = $instance->getLatitud();
            $instanceLongitude = $instance->getLongitud();
            if ($instanceLatitude && $instanceLongitude) {
                $instancesMarkers[] = [
                    'latitude' => addcslashes($instanceLatitude, ','),
                    'longitude' => addcslashes($instanceLongitude, ','),
                    'title' => $instance->getName()
                ];
            }
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

        return array(
            'directory' => $this->getDirectory(),
            'instances' => $cInstances,
            'legacyInstances' => $lInstances,
            'google_maps_api_key' => $this->getParameter('api_key_map'),
            'google_maps_center_position' => ['latitude' => $latitude, 'longitude' => $longitude],
            'google_maps_markers' => $instancesMarkers
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
        $em = $this->container->get('doctrine.orm.entity_manager');

        $paramteros = $request->get('instance_register');

        $entity = new Instance();
        $form = $this->createForm(InstanceRegisterType::class, $entity);
        /*
         * Informacion de la instancia
         * */

        $apellido_nombre = $paramteros['apellido_nombre'];
        $email = $paramteros['email'];

        $country = $paramteros['country'];
        $text_country = $em->getRepository('Celsius3CoreBundle:Country')->find($country);
        $city = $paramteros['city'];
        $text_city = $em->getRepository('Celsius3CoreBundle:City')->find($city);

        $institution = $paramteros['institution'];
        $text_institution = $em->getRepository('Celsius3CoreBundle:Institution')->find($institution);

        $sitio_biblioteca = $paramteros['sitio_biblioteca'];
        $sitio_institucion = $paramteros['sitio_institucion'];
        $sitio_catalogo = $paramteros['sitio_catalogo'];

        $migrar = empty($paramteros['migrar']) ? '' : $paramteros['migrar'];

        $observaciones = $paramteros['observaciones'];

        $texto = "$apellido_nombre solicito agregar una nueva instancia $text_country -  $text_city - $text_institution con la siguiente informacion: <br/>URL Institucion: $sitio_institucion";
        $texto .= "<br/> URL Biblioteca $sitio_biblioteca <br/> URL Catalogo: $sitio_catalogo";
        $texto .= "<br/> Correo de contacto $email";
        $texto .= "<br/> Observaciones: $observaciones";
        if (!empty($migrar)) {
            $texto .= '<br/> Se solicita migración.';
        }

        $parametros = array();
        $parametros['subject'] = 'Nueva Instancia Cargada';
        $parametros['texto'] = $texto;
        $parametros['priority'] = Priority::PRIORITY_MEDIA;
        $parametros['category'] = Category::CATEGORY_NEW_INSTANCE;
        $parametros['typeState'] = TypeState::TYPE_STATE_NEW;

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
