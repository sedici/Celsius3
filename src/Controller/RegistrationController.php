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

use Celsius3\Controller\Base\EmailController;
use Celsius3\Controller\Core\Controller;
use Celsius3\Entity\BaseUser;
use Celsius3\Exception\Exception;

use Celsius3\Form\Type\RegistrationFormType;
use Celsius3\Helper\CustomFieldHelper;
use Celsius3\Helper\InstanceHelper;
use Celsius3\Manager\UserManager;
use Celsius3\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Http\SecurityEvents;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use Twig\Environment;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class RegistrationController extends Controller
{
    // private EmailVerifier $emailVerifier;
    // private InstanceHelper $instanceHelper;
    // private SessionInterface $session;
    // private UserManager $userManager;
    // private Environment $twig;
    // private TokenStorageInterface $tokenStorage;

    // public function __construct(
    //     EmailVerifier $emailVerifier,
    //     InstanceHelper $instanceHelper,
    //     SessionInterface $session,
    //     UserManager $userManager,
    //     Environment $twig,
    //     TokenStorageInterface $tokenStorage
    // ) {
    //     $this->emailVerifier = $emailVerifier;
    //     $this->instanceHelper = $instanceHelper;
    //     $this->session = $session;
    //     $this->userManager = $userManager;
    //     $this->twig = $twig;
    //     $this->tokenStorage = $tokenStorage;
    // }


    #[Route(
        '/public/registration',
        name: 'registration_register',
        methods: ['POST', 'GET']
    )]
    public function register(
        UserPasswordHasherInterface $userPasswordHasher,
        EntityManagerInterface $entityManager,
        CustomFieldHelper $customFieldHelper,
        EmailController $emailController
    ): Response {
        $request = $this->requestStack->getCurrentRequest();
        $user = new BaseUser();
        $user->setEnabled(true);

        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();

            $customFieldHelper->processCustomUserFields($this->instance, $form, $user);

            // generate a signed url and email it to the user
            $emailController->sendEmailConfirmation(
                'verify_email',
                $user,
                (new TemplatedEmail())
                    ->from(new Address('test@prueba.com', 'Test'))
                    ->to($user->getEmail())
                    ->subject('Please Confirm your Email')
                    ->htmlTemplate('registration/confirmation_email.html.twig')
            );

            return $this->redirectToRoute('administration');
        }

        return $this->htmlRenderer->render('register', [
            'form' => $form->createView(),
        ]);
    }


    #[Route(
        '/public/registration/wait_confirmation',
        name: 'registration_wait_confirmation'
    )]
    public function waitConfirmation()
    {
        $email = $this->session->get('fos_user_send_confirmation_email/email');
        $this->session->remove('fos_user_send_confirmation_email/email');
        $user = $this->repository->findUserByEmail($email);

        if (null === $user) {
            throw new NotFoundHttpException(
                sprintf('The user with email "%s" does not exist', $email)
            );
        }

        $instance = $this->instanceHelper->getSessionOrUrlInstance();
        $registrationWaitConfirmationTitle = $instance->get('registration_wait_confirmation_title')->getValue();
        $registrationWaitConfirmationText = $this->htmlRenderer->createTemplate(
            $instance->get('registration_wait_confirmation_text')->getValue()
        )->render(['email' => $email]);

        return $this->htmlRenderer->render(
            'FOSUserBundle:Registration:waitConfirmation.html.twig',
            [
                'user' => $user,
                'registration_wait_confirmation_title' => $registrationWaitConfirmationTitle,
                'registration_wait_confirmation_text' => $registrationWaitConfirmationText,
            ]
        );
    }


    /**
     * Receive the confirmation token from user email provider, login the user.
     */
    public function confirm($token)
    {
        $request = $this->requestStack->getCurrentRequest();
        $user = $this->userManager->findUserByConfirmationToken($token);

        if (null === $user) $this->error('entity_not_found');

        /** @var $dispatcher \Symfony\Component\EventDispatcher\EventDispatcherInterface */
        $dispatcher = $this->get('event_dispatcher');

        $user->setConfirmationToken(null);
        $user->setEnabled(true);

        $event = new GetResponseUserEvent($user, $request);
        $dispatcher->dispatch(FOSUserEvents::REGISTRATION_CONFIRM, $event);
        $userManager->updateUser($user);

        if (null === $response = $event->getResponse()) {
            $url = $this->generateUrl('fos_user_registration_confirmed');
            $response = new RedirectResponse($url);
        }

        $dispatcher->dispatch(
            FOSUserEvents::REGISTRATION_CONFIRMED,
            new FilterUserResponseEvent($user, $request, $response)
        );
        $event_login = new InteractiveLoginEvent($request, $this->tokenStorage->getToken());
        $dispatcher->dispatch(SecurityEvents::INTERACTIVE_LOGIN, $event_login);

        return $response;
    }
}
