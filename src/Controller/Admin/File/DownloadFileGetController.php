<?php

declare(strict_types=1);

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

namespace Celsius3\Controller\Admin\File;

use Celsius3\Controller\Mixin\FileControllerTrait;
use Celsius3\CoreBundle\Entity\BaseUser;
use Celsius3\CoreBundle\Entity\File;
use Celsius3\CoreBundle\Entity\Request;
use Celsius3\CoreBundle\Exception\Exception;
use Celsius3\CoreBundle\Exception\NotFoundException;
use Celsius3\CoreBundle\Manager\FileManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request as HttpRequest;
use Symfony\Component\HttpFoundation\Response;

final class DownloadFileGetController extends AbstractController
{
    use FileControllerTrait;

    private $requestRepository;
    private $fileRepository;
    private $fileManager;

    public function __construct(
        EntityManagerInterface $entityManager,
        FileManager $fileManager
    ) {
        $this->requestRepository = $entityManager->getRepository(Request::class);
        $this->fileRepository = $entityManager->getRepository(File::class);
        $this->fileManager = $fileManager;
    }

    public function __invoke(HttpRequest $httpRequest, $request_id, $file_id): Response
    {
        $request = $this->findRequest($request_id);
        $file = $this->findFile($file_id);
        $filepath = $this->getFilepath($file);
        /** @var BaseUser $user */
        $user = $this->getUser();

        $this->fileManager->registerDownload($request, $file, $httpRequest, $user);

        return $this->download($filepath);
    }

    private function findRequest($request_id)
    {
        $request = $this->requestRepository->find($request_id);
        if (!$request) {
            throw new NotFoundException('exception.not_found.request');
        }

        return $request;
    }

    private function findFile($file_id)
    {
        $file = $this->fileRepository->find($file_id);
        if (!$file) {
            throw new NotFoundException('exception.not_found.file');
        }

        return $file;
    }

    private function getFilepath(File $file): string
    {
        $filepath = $this->fileManager->getUploadRootDir($file) . DIRECTORY_SEPARATOR . $file->getPath();
        if (!file_exists($filepath)) {
            throw Exception::create(Exception::NOT_FOUND, 'exception.file_not_found');
        }

        return $filepath;
    }
}
