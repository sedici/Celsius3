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
    public function instancesAction()
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

        $legacyInstances = $this->getDoctrine()->getManager()
                ->getRepository('Celsius3CoreBundle:LegacyInstance')
                ->createQueryBuilder('li')
                ->select('o, c, li')
                ->innerJoin('li.ownerInstitutions', 'o')
                ->innerJoin('o.country', 'c')
                ->where('li.enabled = true')
                ->andWhere('li INSTANCE OF Celsius3CoreBundle:LegacyInstance')
                ->getQuery()
                ->getResult();

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

        return array(
            'directory' => $this->getDirectory(),
            'instances' => $cInstances,
            'legacyInstances' => $lInstances,
        );
    }

    /**
     * @Template()
     */
    public function statisticsAction()
    {
        return array(
            'directory' => $this->getDirectory()
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
            'directory' => $this->getDirectory()
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
        public function createAction()
    {


        $em = $this->getDoctrine()->getManager();
        $request = $this->get('request_stack')->getCurrentRequest();



        $texto='';


        $ticket=new Ticket();
        $ticket->setSubject('Nueva Instancia Cargada');
        $ticket->setText($texto);

        $em = $this->getDoctrine()->getManager();
        $priority=$em->getRepository('Celsius3TicketBundle:Priority')->find(Priority::PRIORITY_MEDIA);
        $ticket->setPriority($priority);

        $category=$em->getRepository('Celsius3TicketBundle:Category')->find(Category::CATEGORY_NEW_INSTANCE);

        $ticket->setCategory($category);

        $this->persistEntity($ticket);

        $em->flush($ticket);

        $ticketState=new TicketState();

        $typeState=$em->getRepository('Celsius3TicketBundle:TypeState')->find(TypeState::TYPE_STATE_NEW);


        $ticketState->setTypeState($typeState);
        $ticketState->setTickets($ticket);

        $this->persistEntity($ticketState);


        $ticket->setStatusCurrent($ticketState);

        $em->flush($ticket);
        $em->flush($ticketState);


die;
        return array(
            'entity' => $instance,
            'form' => $form->createView(),
            'directory' => $this->getDirectory()
        );
    }








}
