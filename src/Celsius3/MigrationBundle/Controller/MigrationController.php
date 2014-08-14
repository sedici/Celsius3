<?php

/*
 * Celsius3 - Order management
 * Copyright (C) 2014 PrEBi <info@prebi.unlp.edu.ar>
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

namespace Celsius3\MigrationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/migration")
 */
class MigrationController extends Controller
{

    /**
     * Returns the DocumentManager
     *
     * @return DocumentManager
     */
    protected function getDocumentManager()
    {
        return $this->get('doctrine.odm.mongodb.document_manager');
    }

    /**
     * @Route("/", name="migration_index")
     * @Template()
     */
    public function indexAction()
    {
        return array();
    }

    /**
     * @Route("/process", name="migration_process")
     * @Template()
     */
    public function processAction()
    {
        $host = $this->getRequest()->request->get('host');
        $username = $this->getRequest()->request->get('username');
        $password = $this->getRequest()->request->get('password');
        $database = $this->getRequest()->request->get('database');
        $port = $this->getRequest()->request->has('port') ? $this->getRequest()
                ->request->has('port') : null;

        $this->get('celsius3_migration.migration_manager')
                ->migrate($host, $username, $password, $database, $port);

        return array(
            'countries' => $this->getDocumentManager()
                    ->getRepository('Celsius3CoreBundle:Country')
                    ->findAll(),);
    }
}