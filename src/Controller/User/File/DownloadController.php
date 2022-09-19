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

namespace Celsius3\Controller\User\File;

use Celsius3\Entity\File;
use Celsius3\Entity\Request;
use Celsius3\Exception\Exception;
use Celsius3\Exception\NotFoundException;
use Celsius3\Helper\LifecycleHelper;
use Celsius3\Manager\EventManager;
use Celsius3\Manager\FileManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request as HttpRequest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

final class DownloadController extends AbstractController
{
    private $fileManager;
    private $requestRepository;
    private $fileRepository;
    private $tokenStorage;
    private $lifecycleHelper;

    public function __construct(
        FileManager $fileManager,
        EntityManagerInterface $entityManager,
        TokenStorageInterface $tokenStorage,
        LifecycleHelper $lifecycleHelper
    ) {
        $this->fileManager = $fileManager;
        $this->requestRepository = $entityManager->getRepository(Request::class);
        $this->fileRepository = $entityManager->getRepository(File::class);
        $this->tokenStorage = $tokenStorage;
        $this->lifecycleHelper = $lifecycleHelper;
    }

    public function __invoke(HttpRequest $httpRequest, $request, $file): Response
    {
        $request = $this->requestRepository->find($request);
        $file = $this->fileRepository->find($file);

        $filename = $this->fileManager->getUploadRootDir($file) . '/' . $file->getPath();
        if (!file_exists($filename)) {
            throw Exception::create(Exception::NOT_FOUND, 'exception.file_not_found');
        }

        $this->validate($request, $file, $httpRequest);

        $response = new Response();
        $response->headers->set('Content-type', mime_content_type($filename));
        $response->headers->set('Content-Disposition', 'attachment;filename="' . $file->getName() . '"');
        $response->headers->set('Content-length', filesize($filename));
        $response->sendHeaders();
        $response->setContent(readfile($filename));

        return $response;
    }


    protected function validate(Request $request, File $file, HttpRequest $httpRequest): void
    {
        if (!$request) {
            throw new NotFoundException('exception.not_found.order');
        }

        $user = $this->tokenStorage->getToken()->getUser();

        if (!$this->isGranted('ROLE_ADMIN')) {
            if (!$file || ($file->isDownloaded() && !$file->hasDownloadTime()) || !$file->getEnabled(
            ) || $request->getOrder()->getOriginalRequest()->getOwner()->getId() !== $user->getId(
            ) || !$request->getOwner()->getPdf()) {
                throw new NotFoundException('exception.not_found.file');
            }
        }

        $this->fileManager->registerDownload($request, $file, $httpRequest, $user);

        if ($request->getNotDownloadedFiles()->count() === 0) {
            $this->lifecycleHelper->createEvent(EventManager::EVENT__DELIVER, $request);
        }
    }
}
