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

namespace Celsius3\CoreBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Celsius3\CoreBundle\Entity\File;
use Celsius3\CoreBundle\Manager\EventManager;
use Celsius3\CoreBundle\Entity\Request;
use Celsius3\CoreBundle\Controller\Mixin\FileControllerTrait;

/**
 * File controller.
 *
 * @Route("/user/file")
 */
class UserFileController extends BaseController
{

    use FileControllerTrait;

    protected function validate(Request $request, File $file)
    {
        if (!$request) {
            throw $this->createNotFoundException('Order not found.');
        }

        $user = $this->get('security.token_storage')->getToken()->getUser();

        if (!$this->isGranted('ROLE_ADMIN')) {
            if (!$file || $file->getIsDownloaded() || !$file->getEnabled() || $request->getOrder()->getOriginalRequest()->getOwner()->getId() !== $user->getId() || !$request->getOwner()->getPdf()) {
                throw $this->createNotFoundException('File not found.');
            }
        }

        $this->get('celsius3_core.file_manager')
                ->registerDownload($request, $file, $this->get('request_stack')->getCurrentRequest(), $user);

        if ($request->getNotDownloadedFiles()->count() === 0) {
            $this->get('celsius3_core.lifecycle_helper')
                    ->createEvent(EventManager::EVENT__DELIVER, $request);
        }
    }

    /**
     * Downloads the file associated to a File entity.
     *
     * @Route("/{request}/{file}/download", name="user_file_download", options={"expose"=true})
     *
     * @param string $id The entity ID
     */
    public function downloadAction($request, $file)
    {
        return $this->download($request, $file);
    }

}
