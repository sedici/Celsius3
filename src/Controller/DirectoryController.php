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

declare(strict_types=1);

namespace Celsius3\Controller;

use Celsius3\Entity\City;
use Celsius3\Entity\Country;
use Celsius3\Entity\Instance;
use Celsius3\Entity\Institution;
use Celsius3\Entity\News;
use Celsius3\Form\Type\InstanceRegisterType;
use Celsius3\Form\Type\InstanceType;
use Celsius3\Repository\NewsRepository;
use Celsius3\TicketBundle\Entity\Category;
use Celsius3\TicketBundle\Entity\Priority;
use Celsius3\TicketBundle\Entity\TypeState;
use Celsius3\TicketBundle\Helper\TicketHelper;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use function array_key_exists;

/**
 * @Route("/directory/instance")
 */
class DirectoryController extends BaseController
{

    protected TicketHelper $ticketHelper;
    protected NewsRepository $newsRepository;

    public function __construct(
        TicketHelper $ticketHelper,
        NewsRepository $newsRepository,
        ...$args
    ) {
        parent::__construct(... $args);
        $this->ticketHelper = $ticketHelper;
        $this->newsRepository = $newsRepository;
    }


    protected final function getEntity(): string
    { return Instance::class; }

    protected final function getType(): string
    { return InstanceType::class; }

    protected final function getTemplatePrefix(): string
    { return 'Directory/'; }


    protected function getSortDefaults(): array
    {
        return [
            'defaultSortFieldName' => 'e.updatedAt',
            'defaultSortDirection' => 'asc',
        ];
    }


    public function index(): Response
    {
        return $this->baseIndex(formOptions: [
            'directory' => $this->getDirectory(),
            'lastNews' => $this->newsRepository
                ->findLastNews($this->getDirectory()),
        ]);
    }


    public function instances()
    {
        $instances = $this->repository->findAllEnabledAndVisible();

        $current_instances = [];
        $instances_markers = [];
        

        foreach ($instances as $instance) {
            $instOwnerCountryName =
                $instance->getOwnerInstitutions()->first()->getCountry()->getName();

            if (!array_key_exists(
                $instOwnerCountryName,
                $current_instances
            )) {
                $current_instances[$instOwnerCountryName] = [];
            }
            $current_instances[$instOwnerCountryName][] = $instance;

            $instance_latitude = $instance->getLatitud();
            $instance_longitude = $instance->getLongitud();
            if ($instance_latitude && $instance_longitude) {
                $instances_markers[] = [
                    'latitude' => addcslashes(
                        $instance_latitude, ','
                    ),
                    'longitude' => addcslashes(
                        $instance_longitude, ','
                    ),
                    'title' => $instance->getName()
                ];
            }
        }

        $latitude = '-34.9189929';
        $longitude = '-57.9523734';

        return $this->render(
            (string) $this->templatePrefix . 'instances.html.twig',
            [
                'directory' => $this->getDirectory(),
                'instances' => $current_instances,
                'google_maps_api_key' => $this->getParameter('api_key_map'),
                'google_maps_center_position' => compact('latitude', 'longitude'),
                'google_maps_markers' => $instances_markers
            ]
        );
    }


    public function statistics(): Response
    {
        return $this->render(
            (string) $this->templatePrefix . 'statistics.html.twig',
            [
                'directory' => $this->getDirectory(),
            ]
        );
    }


    /**
     * @Route("/instance-register", name="instance_register", options={"expose"=true})
     */
    public function registerInstance(): Response
    {
        $entity = new Instance();
        $form = $this->createForm(
            InstanceRegisterType::class, $entity
        );

        return $this->render(
            (string) $this->templatePrefix . 'registerInstance.html.twig',
            [
                'entity' => $entity,
                'form' => $form->createView(),
                'directory' => $this->getDirectory(),
            ]
        );
    }


    /**
     * @Route("/create-register", name="directory_instance_create", methods={"POST"})
     */
    public function create(Request $request): Response
    {
        $entity_manager = $this->entityManager;

        $parameters = $request->get('instance_register');

        $entity = new Instance();
        $form = $this->createForm(InstanceRegisterType::class, $entity);

        $apellido_nombre = $parameters['apellido_nombre'];
        $email = $parameters['email'];

        $country = $parameters['country'];
        $text_country = $entity_manager->getRepository(Country::class)->find($country);
        $city = $parameters['city'];
        $text_city = $entity_manager->getRepository(City::class)->find($city);

        $institution = $parameters['institution'];
        $text_institution = $entity_manager->getRepository(Institution::class)->find($institution);

        $sitio_biblioteca = $parameters['sitio_biblioteca'];
        $sitio_institucion = $parameters['sitio_institucion'];
        $sitio_catalogo = $parameters['sitio_catalogo'];

        $migrar = empty($parameters['migrar']) ? '' : $parameters['migrar'];

        $observaciones = $parameters['observaciones'];

        $texto = "$apellido_nombre solicito agregar una nueva instancia $text_country -  $text_city - ".
            "$text_institution con la siguiente informacion: ";
        $texto .= "<br/> URL Institucion: $sitio_institucion";
        $texto .= "<br/> URL Biblioteca $sitio_biblioteca";
        $texto .= "<br/> URL Catalogo: $sitio_catalogo";
        $texto .= "<br/> Correo de contacto $email";
        $texto .= "<br/> Observaciones: $observaciones";

        if (!empty($migrar)) {
            $texto .= '<br/> Se solicita migración.';
        }

        $parametros = [];
        $parametros['subject'] = 'Nueva Instancia Cargada';
        $parametros['texto'] = $texto;
        $parametros['priority'] = Priority::PRIORITY_MEDIA;
        $parametros['category'] = Category::CATEGORY_NEW_INSTANCE;
        $parametros['typeState'] = TypeState::TYPE_STATE_NEW;

        $ticket_helper = $this->ticketHelper;
        $ticket_helper->setParametros($parametros);
        $ticket_helper->createTicket();

        return $this->render(
            'Directory/registerInstance.html.twig',
            [
                'entity' => $entity,
                'form' => $form->createView(),
                'directory' => $this->getDirectory(),
            ]
        );
    }
}
