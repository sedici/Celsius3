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

namespace Celsius3\CoreBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\HttpFoundation\File\File;
use Celsius3\CoreBundle\Manager\FileManager;

class LogoToFileTransformer implements DataTransformerInterface
{
    /**
     * Transforms an object (File) to a string (id).
     *
     * @param  String $filename
     * @return File
     */
    public function transform($filename)
    {
        if (!$filename || !file_exists(FileManager::LOGOS_UPLOAD_DIR . '/' . $filename)) {
            return null;
        }

        $file = new File(FileManager::LOGOS_UPLOAD_DIR . '/' . $filename);

        return $file;
    }

    /**
     * Transforms a string (filename) to an object (File).
     *
     * @param  File                       $file
     * @return string
     */
    public function reverseTransform($file)
    {
        return $file;
    }
}
