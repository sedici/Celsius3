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

namespace Celsius3\CoreBundle\Controller\Mixin;

use Celsius3\CoreBundle\Entity\Request;
use Celsius3\CoreBundle\Entity\File;
use Symfony\Component\HttpFoundation\Response;

trait FileControllerTrait
{

    protected function download($request, $file)
    {
        $request = $this->getDoctrine()->getManager()
                        ->getRepository('Celsius3CoreBundle:Request')->find($request);

        $file = $this->getDoctrine()->getManager()
                        ->getRepository('Celsius3CoreBundle:File')->find($file);

        $this->validate($request, $file);
        
        $filename = $file->getUploadRootDir() . '/' . $file->getPath();

        $response = new Response();
        $response->headers->set('Content-type', mime_content_type($filename));
        $response->headers->set('Content-Disposition', 'attachment;filename="' . $file->getName() . '"');
        $response->headers->set('Content-length', filesize($filename));
        $response->sendHeaders();
        $response->setContent(readfile($filename));

        return $response;
    }

    abstract protected function validate(Request $request, File $file);
}
