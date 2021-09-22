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

namespace Celsius3\CoreBundle\Controller;

use FOS\UserBundle\Controller\ResettingController as BaseResettingController;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Celsius3\Exception\Exception;

class ResettingController extends BaseResettingController
{
    public function userResetAction($username)
    {
        /** @var $user UserInterface */
        $user = $this->container->get('fos_user.user_manager')->findUserByUsernameOrEmail($username);
        if (!$user) {
            throw Exception::create(Exception::ENTITY_NOT_FOUND, 'exception.entity_not_found.user');
        }

        if (null === $user->getConfirmationToken()) {
            /** @var $tokenGenerator \FOS\UserBundle\Util\TokenGeneratorInterface */
            $tokenGenerator = $this->container->get('fos_user.util.token_generator');
            $user->setConfirmationToken($tokenGenerator->generateToken());
        }

        $this->container->get('fos_user.mailer')->sendResettingEmailMessage($user);
        $user->setPasswordRequestedAt(new \DateTime());
        $this->container->get('fos_user.user_manager')->updateUser($user);

        $this->container->get('session')->getFlashBag()->add('success', 'The password reset was requested.');

        return new RedirectResponse($this->container->get('router')->generate('admin_user_show', array('id' => $user->getId()), UrlGeneratorInterface::ABSOLUTE_PATH));
    }

    public function checkEmailAction(Request $request)
    {
        $email = $request->query->get('email');

        if (empty($email)) {
            return new RedirectResponse($this->generateUrl('fos_user_resetting_request'));
        }

        $instance = $this->container->get('celsius3_core.instance_helper')->getSessionOrUrlInstance();
        $resettingCheckEmailTitle = $instance->get('resetting_check_email_title')->getValue();
        $resettingCheckEmailText = $this->get('twig')->createTemplate($instance->get('resetting_check_email_text')->getValue())->render(['email' => $email]);

        return $this->render('Celsius3CoreBundle:Resetting:checkEmail.html.twig', array(
            'email' => $email,
            'resetting_check_email_title' => $resettingCheckEmailTitle,
            'resetting_check_email_text' => $resettingCheckEmailText,
        ));
    }

    protected function getObfuscatedEmail(UserInterface $user)
    {
        $email = $user->getEmail();
        if (false !== $pos = strpos($email, '@')) {
            $email = substr($email, 0, 3) . '...' . substr($email, $pos);
        }

        return $email;
    }

    public function sendEmailAction(Request $request)
    {
        $username = $request->request->get('username');

        /** @var $user UserInterface */
        $user = $this->get('fos_user.user_manager')->findUserByUsernameOrEmail($username);

        if (empty($username) || null === $user || !$user->isEnabled()) {
            return $this->render('FOSUserBundle:Resetting:request.html.twig', array(
                'invalid_username' => $username
            ));
        }

        if ($user->isPasswordRequestNonExpired($this->container->getParameter('fos_user.resetting.token_ttl'))) {
            $instance = $this->container->get('celsius3_core.instance_helper')->getSessionOrUrlInstance();
            $resettingPasswordAlreadyRequestedTitle = $instance->get('resetting_password_already_requested_title')->getValue();
            $resettingPasswordAlreadyRequestedText = $this->get('twig')->createTemplate($instance->get('resetting_password_already_requested_text')->getValue())->render([]);

            return $this->render('FOSUserBundle:Resetting:passwordAlreadyRequested.html.twig', [
                'resetting_password_already_requested_title' => $resettingPasswordAlreadyRequestedTitle,
                'resetting_password_already_requested_text' => $resettingPasswordAlreadyRequestedText
            ]);
        }

        if (null === $user->getConfirmationToken()) {
            /** @var $tokenGenerator \FOS\UserBundle\Util\TokenGeneratorInterface */
            $tokenGenerator = $this->get('fos_user.util.token_generator');
            $user->setConfirmationToken($tokenGenerator->generateToken());
        }

        $this->get('fos_user.mailer')->sendResettingEmailMessage($user);
        $user->setPasswordRequestedAt(new \DateTime());
        $this->get('fos_user.user_manager')->updateUser($user);

        return new RedirectResponse($this->generateUrl('fos_user_resetting_check_email',
            array('email' => $this->getObfuscatedEmail($user))
        ));
    }
}
