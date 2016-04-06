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
use Celsius3\CoreBundle\Entity\Journal;
use Celsius3\CoreBundle\Form\Type\JournalType;
use Celsius3\CoreBundle\Filter\Type\JournalFilterType;

/**
 * Location controller.
 *
 * @Route("/admin/journal")
 */
class AdminJournalController extends BaseInstanceDependentController
{

    protected function listQuery($name)
    {
        return $this->getDoctrine()->getManager()
                        ->getRepository('Celsius3CoreBundle:' . $name)
                        ->findForInstanceAndGlobal($this->getInstance(), $this->getDirectory());
    }

    protected function findShowQuery($name, $id)
    {
        return $this->getDoctrine()->getManager()
                        ->getRepository($this->getBundle() . ':' . $name)
                        ->createQueryBuilder('e')
                        ->where('e.instance = :instance_id')
                        ->orWhere('e.instance = :directory_id')
                        ->andWhere('e.id = :id')
                        ->setParameter('instance_id', $this->getInstance()->getId())
                        ->setParameter('directory_id', $this->getDirectory()->getId())
                        ->setParameter('id', $id)
                        ->getQuery()
                        ->getOneOrNullResult();
    }

    protected function getSortDefaults()
    {
        return array(
            'defaultSortFieldName' => 'e.name',
            'defaultSortDirection' => 'asc',
        );
    }

    /**
     * Lists all Journal entities.
     *
     * @Route("/", name="admin_journal")
     * @Template()
     *
     * @return array
     */
    public function indexAction()
    {
        return $this->baseIndex('Journal', $this->createForm(JournalFilterType::class, null, array(
                            'instance' => $this->getInstance(),
        )));
    }

    /**
     * Displays data for a Journal.
     *
     * @Route("/{id}/show", name="admin_journal_show", options={"expose"=true})
     * @Template()
     *
     * @return array
     */
    public function showAction($id)
    {
        $entity = $this->findShowQuery('Journal', $id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Journal.');
        }

        $results = $this->getDoctrine()->getRepository('Celsius3CoreBundle:Event\Event')
                ->getPreviousJournalSearches($this->getInstance(), $entity);

        $searches = [];
        foreach ($results as $search) {
            $searches[$search->getResult()][] = $search;
        }

        return array(
            'entity' => $entity,
            'searches' => $searches
        );
    }

    /**
     * Displays a form to create a new Journal entity.
     *
     * @Route("/new", name="admin_journal_new", options={"expose"=true})
     * @Template()
     *
     * @return array
     */
    public function newAction()
    {
        return $this->baseNew('Journal', new Journal(), JournalType::class, array(
                    'instance' => $this->getInstance(),
        ));
    }

    /**
     * Creates a new Journal entity.
     *
     * @Route("/create", name="admin_journal_create")
     * @Method("post")
     * @Template("Celsius3CoreBundle:AdminJournal:new.html.twig")
     *
     * @return array
     */
    public function createAction()
    {
        return $this->baseCreate('Journal', new Journal(), JournalType::class, array(
                    'instance' => $this->getInstance(),
                        ), 'admin_journal');
    }

    /**
     * Displays a form to edit an existing Journal entity.
     *
     * @Route("/{id}/edit", name="admin_journal_edit")
     * @Template()
     * @param string $id The entity ID
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function editAction($id)
    {
        return $this->baseEdit('Journal', $id, JournalType::class, array(
                    'instance' => $this->getInstance(),
        ));
    }

    /**
     * Edits an existing Journal entity.
     *
     * @Route("/{id}/update", name="admin_journal_update")
     * @Method("post")
     * @Template("Celsius3CoreBundle:AdminJournal:edit.html.twig")
     *
     * @param string $id The entity ID
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function updateAction($id)
    {
        return $this->baseUpdate('Journal', $id, JournalType::class, array(
                    'instance' => $this->getInstance(),
                        ), 'admin_journal');
    }

    protected function findQuery($name, $id)
    {
        $um = $this->container->get('celsius3_core.user_manager');

        $qb = $this->getDoctrine()->getManager()
                ->getRepository($this->getBundle() . ':' . $name)
                ->createQueryBuilder('e');

        if ($um->getCurrentRole($this->getUser()) !== 'ROLE_SUPER_ADMIN') {
            $qb = $qb->andWhere('e.instance = :instance_id')
                    ->setParameter('instance_id', $this->getInstance()->getId());
        }

        return $qb->andWhere('e.id = :id')
                        ->setParameter('id', $id)
                        ->getQuery()
                        ->getOneOrNullResult();
    }

}
