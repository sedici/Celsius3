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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Celsius3\CoreBundle\Entity\File;
use Celsius3\CoreBundle\Manager\EventManager;
use Celsius3\CoreBundle\Entity\Order;
use Celsius3\CoreBundle\Controller\Mixin\FileControllerTrait;

/**
 * File controller.
 *
 * @Route("/user/file")
 */
class UserFileController extends BaseController
{
    use FileControllerTrait;

    protected function validate(Order $order, File $file)
    {
        if (!$order) {
            return $this->createNotFoundException('Order not found.');
        }

        $user = $this->get('security.context')->getToken()->getUser();

        if (!$file || $file->getIsDownloaded()
                || $order->getOwner()->getId() != $user->getId()) {
            return $this->createNotFoundException('File not found.');
        }

        $this->get('celsius3_core.file_manager')
                ->registerDownload($order, $file, $this->getRequest(), $user);

        if ($order->getNotDownloadedFiles()->count() == 0) {
            $this->get('celsius3_core.lifecycle_helper')
                    ->createEvent(EventManager::EVENT__DELIVER, $order);
        }
    }

    /**
     * Downloads the file associated to a File entity.
     *
     * @Route("/{order}/{file}/download", name="user_file_download", options={"expose"=true})
     * @Method("post")
     *
     * @param string $id The entity ID
     */
    public function downloadAction($request, $file)
    {
        return $this->download($request, $file);
    }
}