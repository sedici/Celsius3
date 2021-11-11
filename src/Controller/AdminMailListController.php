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

use Celsius3\CoreBundle\Entity\Email;
use Celsius3\Exception\Exception;
#use Celsius3\Form\Type\MailType;
use Celsius3\Form\Type\Filter\MailFilterType; 
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;



/**
 * MailList controller.
 *
 * @Route("/admin/maillist")
 */
class AdminMailListController extends BaseInstanceDependentController
{
    
    /**
     * Lists all Mail entities.
     *
     * @Route("/", name="admin_maillist")
     * @Template()
     *
     * @return array
     */
    public function index()
    {
        return $this->baseIndex('Email', $this->createForm(MailFilterType::class, null, array(
            'instance' => $this->getInstance(),
        )));
    }
}
