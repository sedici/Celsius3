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

declare(strict_types=1);

namespace Celsius3\Manager;

use Celsius3\Entity\BaseUser;
use Celsius3\Entity\Event\Event;
use Celsius3\Entity\File;
use Celsius3\Entity\FileDownload;
use Celsius3\Entity\Instance;
use Celsius3\Entity\Request;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use RuntimeException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

class FileManager
{

    public function __construct(
        protected string $uploadRootDir,
        protected string $logosUploadDir,
        protected EntityManagerInterface $entityManager
    ) { }

    public function getLogosUploadDir(): string
    {
        return $this->logosUploadDir;
    }

    public function uploadFiles(Request $request, $event, array $files = []): void
    {
        foreach ($files as $uploaded_file) {
            $file = new File();
            $file->setName($uploaded_file->getClientOriginalName());
            $file->setFile($uploaded_file);
            $file->setEvent($event);
            $file->setRequest($request);
            $file->setEnabled(true);
            $file->setPages($this->countPages($uploaded_file));
            $file->setInstance($request->getInstance());
            $event->addFile($file);
        }

    }

    private function countPages(UploadedFile $file): int
    {
        exec('exiftool '.$file->getRealPath()." | awk '/Page Count/ { print $4 }'", $output);

        return (int) $output[0];
    }


    public function registerDownload(
        Request $request,
        File $file,
        HttpRequest $httpRequest,
        BaseUser $user
    ): void {
        if (!$user->hasRole('ROLE_ADMIN') && !$user->hasRole('ROLE_SUPER_ADMIN')) {
            $file->setDownloaded(true);
        }

        $download = new FileDownload();
        $download->setIp($httpRequest->getClientIp());

        $download->setUser($user);

        $download->setUserAgent($httpRequest->headers->get('user-agent'));
        $download->setFile($file);
        $download->setRequest($request);

        $download->setInstance($user->getInstance());

        $this->entityManager->persist($file);
        $this->entityManager->persist($download);
        $this->entityManager->flush();
    }


    public function copyFilesToPreviousRequest(
        Request $previousRequest,
        Request $request,
        $event
    ): void {
        foreach ($request->getFiles() as $original) {
            if ($original->getEnabled()) {
                $file = clone $original;
                $file->setInstance($previousRequest->getInstance());
                $file->setRequest($previousRequest);
                $file->setEvent($event);

                if (empty($original->getPath())) throw new Exception('Copy file error');

                $fromPath = $this->getUploadRootDir($original).DIRECTORY_SEPARATOR.$original->getPath();
                $toPath = $this->getUploadRootDir($file).DIRECTORY_SEPARATOR.$file->getPath();
                if ($fromPath !== $toPath && !copy($fromPath, $toPath)) {
                    throw new Exception('Copy file error');
                }

                $this->entityManager->persist($file);
                $event->addFile($file);
                $this->entityManager->persist($event);
            }
        }
    }


    public function getUploadRootDir(File $file): string
    {
        return $this->uploadRootDir.$file->getInstance()->getUrl();
    }


    public function createFilesDirectory($url): void
    {
        $path = $this->uploadRootDir.$url;

        if (!file_exists($path)) {
            if (!mkdir($path, 0755) && !is_dir($path)) {
                throw new RuntimeException(sprintf('El directorio "%s" no fue creado', $path));
            }
            chown($path, 'www-data');
        }
    }


    public function updateFilesDirectory($oldUrl, $newUrl): void
    {
        $old_path = $this->uploadRootDir.$oldUrl;
        $new_path = $this->uploadRootDir.$newUrl;

        if ($old_path !== $new_path && file_exists($old_path)) {
            rename($old_path, $new_path);
        }
    }


    protected function getUploadDir(): string
    {
        return 'uploads'.DIRECTORY_SEPARATOR.$this->getInstance()->getUrl();
    }
}
