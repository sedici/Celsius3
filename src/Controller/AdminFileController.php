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
use Celsius3\Entity\File;
use Celsius3\Entity\Request;
use Celsius3\Controller\Mixin\FileControllerTrait;
use Celsius3\Form\Type\FileType;
use Celsius3\Manager\FileManager;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

use Celsius3\Helper\ConfigurationHelper;
use Celsius3\Manager\InstanceManager;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Celsius3\Helper\InstanceHelper;
use Celsius3\Manager\FilterManager;
use Celsius3\Manager\UnionManager;
use Celsius3\Manager\UserManager;
use Symfony\Contracts\Translation\TranslatorInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * File controller.
 *
 * @Route("/admin/file")
 */
class AdminFileController extends BaseEntityController
{
    
    use FileControllerTrait;

    protected $tokenStorage;

    protected $fileManager;

    public function __construct(
        TokenStorageInterface $tokenStorage,
        FileManager $fileManager,
        InstanceManager $instanceManager,
        EntityManagerInterface $entityManager,
        PaginatorInterface $paginator,
        ConfigurationHelper $configurationHelper,
        TranslatorInterface $translator,
        ManagerRegistry $managerRegistry,
        RequestStack $requestStack,
        UnionManager $unionManager,
        UserManager $userManager,
        FilterManager $filterManager,
        InstanceHelper $instanceHelper
    ) {
        parent::__construct(
            $instanceManager,
            $entityManager,
            $paginator,
            $configurationHelper,
            $translator,
            $managerRegistry,
            $requestStack,
            $unionManager,
            $userManager,
            $filterManager,
            $instanceHelper
        );
        $this->tokenStorage = $tokenStorage;
        $this->fileManager = $fileManager;
    }

    protected function getEntity(): string
    { return File::class; }

    protected final function getType(): string
    { return FileType::class; }


    protected function getSortDefaults(): array
    {
        return [
            'defaultSortFieldName' => 'e.updatedAt',
            'defaultSortDirection' => 'desc',
        ];
    }


    protected function validate(
        Request $request, File $file
    ): void {
        if (!$request) $this->error('exception_not_found');

        if (!$file) $this->error('exception_not_found');

        $user = $this->tokenStorage->getToken()->getUser();

        $httpRequest = $this->requestStack->getCurrentRequest();

        $this->fileManager->registerDownload(
            $request, $file,
            $httpRequest, $user
        );
    }


    /**
     * Downloads the file associated to a File entity.
     *
     * @Route("/{request}/{file}/download", name="admin_file_download_file", options={"expose"=true})
     *
     * @param string $id The entity ID
     */
    public function download($request, $file): mixed
    {
        # No es recursivo, usa el Trait
        return $this->download($request, $file);
    }

}
