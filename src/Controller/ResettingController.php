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

//use FOS\UserBundle\Controller\ResettingController as BaseResettingController;
//use FOS\UserBundle\Model\UserInterface;

use Celsius3\Entity\Instance;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Celsius3\Exception\Exception;
use Celsius3\Helper\InstanceHelper;
use Celsius3\Mailer\FosMailer;
use Celsius3\Manager\FosUserManager;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Twig\Environment;

class ResettingController extends AbstractController //extends BaseResettingController
{

    protected FosUserManager $userManager;
    protected TokenGeneratorInterface $tokenGenerator;
    protected FosMailer $mailer;
    protected RouterInterface $router;
    protected RequestStack $requestStack;
    protected InstanceHelper $instanceHelper;
    protected Instance $instance;
    private Environment $twig;


    public function __construct(
        FosUserManager $userManager,
        TokenGeneratorInterface $tokenGenerator,
        FosMailer $mailer,
        RouterInterface $router,
        RequestStack $requestStack,
        InstanceHelper $instanceHelper,
        Environment $twig, 
    ) {
        $this->userManager = $userManager;
        $this->tokenGenerator = $tokenGenerator;
        $this->mailer;
        $this->router = $router;
        $this->requestStack = $requestStack;
        $this->instanceHelper = $instanceHelper;
        $this->instance = $this->getInstance();
        $this->twig = $twig;
    }


    protected function getInstance(): Instance
    { return $this->instanceHelper->getSessionInstance(); }


    public function userReset($username): RedirectResponse
    {
        $user = $this->userManager
            ->findUserByUsernameOrEmail($username);

        if (!$user) {
            throw Exception::create(
                Exception::ENTITY_NOT_FOUND,
                'exception.entity_not_found.user'
            );
        }

        if (null === $user->getConfirmationToken()) {
            $user->setConfirmationToken($this->tokenGenerator->generateToken());
        }

        $this->mailer->sendResettingEmailMessage($user);
        $user->setPasswordRequestedAt(new \DateTime());
        $this->userManager->updateUser($user);

        $this->addFlash('success', 'The password reset was requested.');

        return new RedirectResponse(
            $this->router->generate(
                'admin_user_show',
                [ 'id' => $user->getId() ],
                UrlGeneratorInterface::ABSOLUTE_PATH
            )
        );
    }


    public function checkEmail(): RedirectResponse|Response
    {
        $request = $this->requestStack->getCurrentRequest();
        $email = $request->query->get('email');

        if (empty($email)) {
            return new RedirectResponse(
                $this->generateUrl('fos_user_resetting_request')
            );
        }

        $instance = $this->instance;
        $resettingCheckEmailTitle = $instance
            ->get('resetting_check_email_title')->getValue();
        $resettingCheckEmailText = $this->twig->createTemplate(
            $instance
                ->get('resetting_check_email_text')
                ->getValue()
            )->render(['email' => $email]);

        return $this->render(
            'bundles/FOSUserBundle/Resetting/checkEmail.html.twig',
            [
                'email' => $email,
                'resetting_check_email_title' => $resettingCheckEmailTitle,
                'resetting_check_email_text' => $resettingCheckEmailText,
            ]
        );
    }


    protected function getObfuscatedEmail(UserInterface $user)
    {
        $email = $user->getEmail();
        if (false !== $pos = strpos($email, '@')) {
            $email =
                substr($email, 0, 3)
                . '...'
                . substr($email, $pos);
        }

        return $email;
    }


    public function sendEmail(Request $request): RedirectResponse|Response
    {
        $request = $this->requestStack->getCurrentRequest();
        $username = $request->get('username');

        $user = $this->userManager
            ->findUserByUsernameOrEmail($username);

        if (empty($username) || null === $user || !$user->isEnabled()) {
            return $this->render(
                'FOSUserBundle:Resetting:request.html.twig',
                [
                    'invalid_username' => $username
                ]
            );
        }

        if ($user->isPasswordRequestNonExpired(
            $this->container
                ->getParameter('fos_user.resetting.token_ttl')
        )) {
            $instance = $this->instanceHelper->getSessionOrUrlInstance();
            $resettingPasswordAlreadyRequestedTitle = $instance
                ->get('resetting_password_already_requested_title')->getValue();
            $resettingPasswordAlreadyRequestedText = $this->twig
                ->createTemplate(
                    $instance->get(
                        'resetting_password_already_requested_text'
                    )->getValue()
                )->render([]);

            return $this->render(
                'FOSUserBundle:Resetting:passwordAlreadyRequested.html.twig',
                [
                    'resetting_password_already_requested_title' => $resettingPasswordAlreadyRequestedTitle,
                    'resetting_password_already_requested_text' => $resettingPasswordAlreadyRequestedText
                ]
            );
        }

        if (null === $user->getConfirmationToken())
            $user->setConfirmationToken($this->tokenGenerator->generateToken());

        $this->mailer->sendResettingEmailMessage($user);
        $user->setPasswordRequestedAt(new \DateTime());
        $this->userManager->updateUser($user);

        return new RedirectResponse(
            $this->generateUrl(
                'fos_user_resetting_check_email',
                [ 'email' => $this->getObfuscatedEmail($user) ]
            )
        );
    }
}
