<?php

/*
 * Celsius3 - Registration controller
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

use Celsius3\Controller\Base\EmailController;
use Celsius3\Controller\Base\EmailTemplateController;
use Celsius3\Controller\Base\UserController;
use Celsius3\Entity\BaseUser;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Celsius3\Form\Type\RegistrationFormType;

use Celsius3\Controller\Core\HtmlRenderer;
use Celsius3\Controller\Core\RestRenderer;
use Celsius3\EntityManager\ThreadManager;
use Celsius3\Exception\Exception;
use Celsius3\Helper\ConfigurationHelper;
use Celsius3\Helper\CustomFieldHelper;
use Celsius3\Manager\InstanceManager;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Celsius3\Helper\InstanceHelper;
use Celsius3\Manager\FilterManager;
use Celsius3\Manager\UnionManager;
use Celsius3\Manager\UserManager;
use Symfony\Contracts\Translation\TranslatorInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Validator\ValidatorInterface;


#[Route('/auth/signin')]
class RegistrationController extends UserController
{

    public function __construct(
        protected UserPasswordHasherInterface $passwordHasher,
        protected EmailTemplateController $emailTemplateController,
        protected EmailController $emailController,
        ThreadManager $threadManager,
        CustomFieldHelper $customFieldHelper,
        ValidatorInterface $validator,
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
            $threadManager,
            $customFieldHelper,
            $validator,
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


    #[Route(
        '/',
        name: 'signin',
        methods: ['POST', 'GET']
    )]
    public function register(): Response {
        $request = $this->requestStack->getCurrentRequest();
        $user = new BaseUser();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $this->passwordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $user->generateConfirmationToken();
            $user->setEnabled(false);
            $this->persistEntity($user);

            // Generar URL de confirmación
            $confirmationUrl = $this->generateUrl(
                'signin_confirmation',
                ['token' => $user->getConfirmationToken()],
                UrlGeneratorInterface::ABSOLUTE_URL
            );

            try {
                $this->emailController->sendMimeEmail(
                    'test@prueba.com',
                    $user->getEmail(),
                    'Please Confirm your Email',
                    $this->emailTemplateController->renderTemplate(
                        'user_confirmation',
                        [
                            'user' => $user,
                            'instance' => $this->instance,
                            'url' => $confirmationUrl,
                        ]
                    )
                );
            } catch (\Exception $e) {
                $this->addFlash(
                    'error',
                    'No se pudo enviar el correo de confirmación. '
                        . 'Causa: '. $e->getMessage()
                );
                return $this->redirectToRoute('signin');
            }

            return $this->redirectToRoute('administration');
        }

        return $this->htmlRenderer->render(
            'register',
            [ 'form' => $form->createView() ]
        );
    }


    #[Route(
        '/confirmation/{token}',
        name: 'signin_confirmation',
        requirements: [ "token" => "[^/]+?" ]
    )]
    public function confirm(
        string $token
    ): Response {
        $user = $this->repository->findOneBy(
            [ 'confirmationToken' => $token ]
        );
    
        if (!$user) $this->error(Exception::ENTITY_NOT_FOUND);

        // Confirmar la cuenta
        $user->setConfirmationToken(null);
        $user->setEnabled(true);
        $this->entityManager->flush();

        return $this->htmlRenderer->render(
            'confirmed',
            [ 'user' => $user ]
        );
    }
}
