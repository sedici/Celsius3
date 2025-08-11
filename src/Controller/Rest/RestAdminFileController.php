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

namespace Celsius3\Controller\Rest;

use Celsius3\Controller\Base\FileController;
use Celsius3\Controller\Mixin\FileControllerTrait;
use Celsius3\Entity\File;
use Celsius3\Entity\Request;
use FOS\RestBundle\Controller\Annotations\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;

use Celsius3\Controller\Core\HtmlRenderer;
use Celsius3\Controller\Core\RestRenderer;
use Celsius3\Helper\ConfigurationHelper;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Celsius3\Helper\InstanceHelper;
use Celsius3\Manager\FileManager;
use Celsius3\Manager\FilterManager;
use Celsius3\Manager\UnionManager;
use Celsius3\Manager\UserManager;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Validator\ValidatorInterface;


#[
    Route('/rest/v1/admin/file'),
    IsGranted('ROLE_ADMIN')
]
class RestAdminFileController extends FileController
{

    use FileControllerTrait;

    public function __construct(
        readonly protected FileManager $fileManager,
        ValidatorInterface $validator,
        EntityManagerInterface $entityManager,
        PaginatorInterface $paginator,
        ConfigurationHelper $configurationHelper,
        TranslatorInterface $translator,
        RequestStack $requestStack,
        UnionManager $unionManager,
        UserManager $userManager,
        FilterManager $filterManager,
        InstanceHelper $instanceHelper,
        FormFactoryInterface $formFactory,
        SessionInterface $session,
        RouterInterface $router,
        TokenStorageInterface $tokenStorage,
        Security $security,
        HtmlRenderer $htmlRenderer,
        RestRenderer $restRenderer
    ) {
        parent::__construct(
            $validator,
            $entityManager,
            $paginator,
            $configurationHelper,
            $translator,
            $requestStack,
            $unionManager,
            $userManager,
            $filterManager,
            $instanceHelper,
            $formFactory,
            $session,
            $router,
            $tokenStorage,
            $security,
            $htmlRenderer,
            $restRenderer
        );
    }

    public function initialize(): void
    {
        parent::initialize();
        $this->setInstanceDependent(false);
    }


    // /**
    //  * @Get("/{id}", name="rest_admin_file", options={"expose"=true})
    //  */
    // public function getContact(string $id): Response
    // { return $this->restRenderer->show($id, 'administration_order_show'); }


    #[Route(
        '/{file_id}/state',
        name: 'admin_rest_file_state',
        options: ['expose' => true],
        methods: ['POST']
    )]
    public function changeState(string $file_id): Response
    {
        $file = $this->repository->find($file_id);
        if (!$file) $this->error('not_found', File::class);

        $file->setEnabled(!$file->getEnabled());
    
        $this->persistEntity($file);

        return $this->restRenderer->render(
            $file, serializerGroups: 'administration_order_show'
        );
    }


    protected function validate(Request $request, File $file): void
    {
        $user = $this->getUser();
        $httpRequest = $this->requestStack->getCurrentRequest();
        $this->fileManager->registerDownload(
            $request, $file,
            $httpRequest, $user
        );
    }


    #[Route(
        '/{request}/{file}/download',
        name: 'admin_file_download_file',
        options: ['expose' => true],
        methods: ['GET']
    )]
    public function downloadFile(string $request, string $file): mixed
    {
        return $this->downloadFileFromRequest($request, $file);
    }
}
