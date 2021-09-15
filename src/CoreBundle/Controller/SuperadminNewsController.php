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
use Celsius3\CoreBundle\Entity\News;
use Celsius3\Form\Type\NewsType;
use Celsius3\Form\Type\Filter\NewsFilterType;

/**
 * News controller.
 *
 * @Route("/superadmin/news")
 */
class SuperadminNewsController extends BaseController
{
    protected function listQuery($name)
    {
        return $this->getDoctrine()->getManager()
                        ->getRepository('Celsius3CoreBundle:'.$name)
                        ->createQueryBuilder('e')
                        ->where('e.instance = :instance')->setParameter('instance', $this->getDirectory()->getId());
    }

    /**
     * Lists all News entities.
     *
     * @Route("/", name="superadmin_news")
     * @Template()
     *
     * @return array
     */
    public function indexAction()
    {
        return $this->baseIndex('News', $this->createForm(NewsFilterType::class));
    }

    /**
     * Finds and displays a News entity.
     *
     * @Route("/{id}/show", name="superadmin_news_show")
     * @Template()
     *
     * @param string $id The entity ID
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function showAction($id)
    {
        return $this->baseShow('News', $id);
    }

    /**
     * Displays a form to create a new News entity.
     *
     * @Route("/new", name="superadmin_news_new")
     * @Template()
     *
     * @return array
     */
    public function newAction()
    {
        return $this->baseNew('News', new News(), NewsType::class, array(
            'instance' => $this->getDirectory(),
        ));
    }

    /**
     * Creates a new News entity.
     *
     * @Route("/create", name="superadmin_news_create")
     * @Method("post")
     * @Template("Celsius3CoreBundle:SuperadminNews:new.html.twig")
     *
     * @return array
     */
    public function createAction()
    {
        return $this->baseCreate('News', new News(), NewsType::class, array(
            'instance' => $this->getDirectory(),
        ), 'superadmin_news');
    }

    /**
     * Displays a form to edit an existing News entity.
     *
     * @Route("/{id}/edit", name="superadmin_news_edit")
     * @Template()
     *
     * @param string $id The entity ID
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function editAction($id)
    {
        return $this->baseEdit('News', $id, NewsType::class, array(
            'instance' => $this->getDirectory(),
        ));
    }

    /**
     * Edits an existing News entity.
     *
     * @Route("/{id}/update", name="superadmin_news_update")
     * @Method("post")
     * @Template("Celsius3CoreBundle:SuperadminNews:edit.html.twig")
     *
     * @param string $id The entity ID
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function updateAction($id)
    {
        return $this->baseUpdate('News', $id, NewsType::class, array(
            'instance' => $this->getDirectory(),
        ), 'superadmin_news');
    }
}
