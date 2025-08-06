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


namespace Celsius3\Controller\Html\Auth;

use Celsius3\Controller\Base\EmailController;
use Celsius3\Controller\Base\UserController;
use Celsius3\Entity\BaseUser;
use Celsius3\Form\Type\ChangePasswordFormType;
use Celsius3\Form\Type\ResetPasswordRequestFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\ResetPassword\Exception\ResetPasswordExceptionInterface;
use SymfonyCasts\Bundle\ResetPassword\ResetPasswordHelperInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

use Celsius3\Controller\Core\HtmlRenderer;
use Celsius3\Controller\Core\RestRenderer;
use Celsius3\EntityManager\ThreadManager;
use Celsius3\Helper\ConfigurationHelper;
use Celsius3\Helper\CustomFieldHelper;
use Celsius3\Helper\InstanceHelper;
use Knp\Component\Pager\PaginatorInterface;
use Celsius3\Manager\FilterManager;
use Celsius3\Manager\UnionManager;
use Celsius3\Manager\UserManager;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;



#[Route('/public/reset-password')]
class ResetPasswordController extends UserController
{

    public function __construct(
        protected EmailController $emailController,
        protected ResetPasswordHelperInterface $resetPasswordHelper,
        TokenGeneratorInterface $tokenGenerator,
        ThreadManager $threadManager,
        CustomFieldHelper $customFieldHelper,
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
            $tokenGenerator,
            $threadManager,
            $customFieldHelper,
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
        $this->htmlRenderer->setTemplatePrefix('reset_password/');
    }


    #[Route("/", name: "password_forgotten", methods: ['GET', 'POST'])]
    public function passwordForgotten(): Response
    {
        $request = $this->requestStack->getCurrentRequest();
        $form = $this->createForm(ResetPasswordRequestFormType::class, hasData: false);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()){
                $user = $this->repository->findOneBy(
                    ['email' => $form->get('email')->getData()
                ]);
    
                if (!$user) {
                    $this->addFlash(
                        'error',
                        $this->translator->trans(
                            "There's no user with the specified email address."
                        )
                    );
                    return $this->redirectToRoute('password_forgotten');
                }

                return $this->resetPasswordToUser(
                    $user, 'password_forgotten'
                );
            } else {
                $errors = $form->getErrors(true);

                $errorMessage = implode(
                    PHP_EOL,
                    array_map(fn (FormError $error): string =>
                        $this->translator->trans($error->getMessage()),
                        iterator_to_array($errors)
                    )
                );

                $this->addFlash('error', $errorMessage);
            }
        }

        return $this->htmlRenderer->render(
            'request',
            [ 'form' => $form->createView() ]
        );
    }


    #[
        Route(
            "/from-admin/{userId}",
            name: "reset_password_from_admin",
            methods: ['POST']
        ),
        IsGranted('ROLE_ADMIN')
    ]
    public function resetPasswordFromAdmin(): Response
    {
        $request = $this->requestStack->getCurrentRequest();
        $userId = $request->get('userId');

        $user = $this->repository->find($userId);

        if (!$user) {
            $this->addFlash(
                'error',
                $this->translator->trans(
                    "There's no user with the specified email address."
                )
            );
            return $this->redirect($request->headers->get('referer'));
        }

        return $this->resetPasswordToUser($user, 'administration');
    }


    public function resetPasswordToUser(BaseUser $user, string $redirectionRoute): Response
    {
        try {
            $resetToken = $this->resetPasswordHelper->generateResetToken($user);
        } catch (ResetPasswordExceptionInterface $e) {
            $this->addFlash(
                'error',
                $this->translator->trans(
                    'There was an unexpected error: ' . $e->getReason()
                )
            );
            return $this->redirectToRoute($redirectionRoute);
        }

        $url = $this->router->generate(
            'reset_password',
            ['token' => $resetToken->getToken()],
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        // Genera la url dependiendo de la instancia de donde sea el usuario
        $components = parse_url($url);
        $path = $components['path'] ?? '';
        $query = isset($components['query']) ? '?' . $components['query'] : '';

        $url = $user->getInstance()->getWebsite() . $path . $query;

        $this->emailController->sendTemplateEmail(
            $user,
            'resetting',
            [ 'url' => $url ]
        );

        return $this->redirectToRoute('check_email');
    }


    #[Route("/check-email", name: "check_email")]
    public function checkEmail(): Response
    {
        // Siempre genera token falso para evitar user enumeration
        $resetToken = $this->resetPasswordHelper->generateFakeResetToken();
        
        return $this->htmlRenderer->render(
            'check_email',
            ['resetToken' => $resetToken]
        );
    }


    #[Route("/reset/{token}", name: "reset_password", methods: ['GET', 'POST'])]
    public function reset(
        UserPasswordHasherInterface $userPasswordHasher,
        string $token
    ): Response {
        $request = $this->requestStack->getCurrentRequest();

        try {
            $user = $this->resetPasswordHelper->validateTokenAndFetchUser($token);
        } catch (ResetPasswordExceptionInterface $e) {
            $this->addFlash(
                'reset_password_error',
                sprintf(
                    '%s - %s',
                    $this->translator->trans(
                        ResetPasswordExceptionInterface::MESSAGE_PROBLEM_VALIDATE,
                        [],
                        'ResetPasswordBundle'
                    ),
                    $this->translator->trans($e->getReason(), [], 'ResetPasswordBundle')
                )
            );
            return $this->redirectToRoute('password_forgotten');
        }

        $form = $this->createForm(ChangePasswordFormType::class, hasData: false);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->resetPasswordHelper->removeResetRequest($token);
            
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $this->entityManager->flush();

            $session = $this->requestStack->getCurrentRequest()->getSession();

            $session->remove('ResetPasswordPublicToken');
            $session->remove('ResetPasswordCheckEmail');
            $session->remove('ResetPasswordToken');

            return $this->redirectToRoute('login');
        }

        return $this->htmlRenderer->render(
            'reset',
            [
                'form' => $form->createView(),
                'token' => $token
            ]
        );
    }
}