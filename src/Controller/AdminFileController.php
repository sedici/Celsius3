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

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Celsius3\CoreBundle\Entity\File;
use Celsius3\CoreBundle\Entity\Request;
use Celsius3\Controller\Mixin\FileControllerTrait;
use Celsius3\Exception\NotFoundException;

/**
 * File controller.
 *
 * @Route("/admin/file")
 */
class AdminFileController extends BaseController
{

    use FileControllerTrait;

    protected function validate(Request $request, File $file)
    {
        if (!$request) {
            throw new NotFoundException('exception.not_found.request');
        }

        if (!$file) {
            throw new NotFoundException('exception.not_found.file');
        }

        $user = $this->get('security.token_storage')->getToken()->getUser();

        $httpRequest = $this->get('request_stack')->getCurrentRequest();

        $this->get('celsius3_core.file_manager')->registerDownload($request, $file, $httpRequest, $user);
    }

    /**
     * Downloads the file associated to a File entity.
     *
     * @Route("/{request}/{file}/download", name="admin_file_download_file", options={"expose"=true})
     *
     * @param string $id The entity ID
     */
    public function download($request, $file)
    {
        return $this->download($request, $file);
    }

}
