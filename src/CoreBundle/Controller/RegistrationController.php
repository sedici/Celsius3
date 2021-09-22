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

use Celsius3\Exception\Exception;
use FOS\UserBundle\Controller\RegistrationController as BaseRegistrationController;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\Event\UserEvent;
use FOS\UserBundle\FOSUserEvents;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Http\SecurityEvents;

class RegistrationController extends BaseRegistrationController
{
    public function registerAction(Request $request)
    {
        /** @var $formFactory \FOS\UserBundle\Form\Factory\FactoryInterface */
        $formFactory = $this->container->get('fos_user.registration.form.factory');
        /** @var $userManager \FOS\UserBundle\Model\UserManagerInterface */
        $userManager = $this->container->get('fos_user.user_manager');
        /** @var $dispatcher \Symfony\Component\EventDispatcher\EventDispatcherInterface */
        $dispatcher = $this->container->get('event_dispatcher');

        $user = $userManager->createUser();
        $user->setEnabled(true);

        $dispatcher->dispatch(FOSUserEvents::REGISTRATION_INITIALIZE, new UserEvent($user, $request));

        $form = $formFactory->createForm();
        $form->setData($user);

        if ('POST' === $request->getMethod()) {
            $form->handleRequest($request);

            if ($email = $form->get('email')->getData()) {
                $emailDomain = (strpos($email, '@')) ? explode('@', $email)[1] : '';
                $instanceDomain = $this->getInstance()->get('email_domain_for_registration')->getValue();
                if ($instanceDomain && $emailDomain && $emailDomain !== $instanceDomain) {
                    $error = new FormError($this->get('translator')->trans('invalid.email.domain', ['%domain%' => $instanceDomain], 'Celsius3CoreBundle_Form'));
                    $form->get('email')->addError($error);
                }
            }

            if ($form->isValid()) {
                $event = new FormEvent($form, $request);
                $dispatcher->dispatch(FOSUserEvents::REGISTRATION_SUCCESS, $event);

                $userManager->updateUser($user);
                $this->container->get('celsius3_core.custom_field_helper')->processCustomUserFields($this->getInstance(), $form, $user);

                if (null === $response = $event->getResponse()) {
                    $url = $this->container->get('router')->generate('fos_user_registration_confirmed');
                    $response = new RedirectResponse($url);
                }

                $dispatcher->dispatch(FOSUserEvents::REGISTRATION_COMPLETED, new FilterUserResponseEvent($user, $request, $response));

                return $response;
            }
        }

        return $this->render('FOSUserBundle:Registration:register.html.twig', array(
                    'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/public/registration/wait_confirmation", name="registration_wait_confirmation")
     */
    public function waitConfirmationAction()
    {
        $email = $this->container->get('session')->get('fos_user_send_confirmation_email/email');
        $this->container->get('session')->remove('fos_user_send_confirmation_email/email');
        $user = $this->container->get('fos_user.user_manager')->findUserByEmail($email);

        if (null === $user) {
            throw new NotFoundHttpException(
            sprintf('The user with email "%s" does not exist', $email));
        }

        $instance = $this->container->get('celsius3_core.instance_helper')->getSessionOrUrlInstance();
        $registrationWaitConfirmationTitle = $instance->get('registration_wait_confirmation_title')->getValue();
        $registrationWaitConfirmationText = $this->get('twig')->createTemplate($instance->get('registration_wait_confirmation_text')->getValue())->render(['email' => $email]);

        return $this->render('FOSUserBundle:Registration:waitConfirmation.html.twig', array(
                    'user' => $user,
                    'registration_wait_confirmation_title' => $registrationWaitConfirmationTitle,
                    'registration_wait_confirmation_text' => $registrationWaitConfirmationText,
        ));
    }

    /**
     * Returns the EntityManager.
     *
     * @return EntityManager
     */
    protected function getEntityManager()
    {
        return $this->container->get('doctrine.orm.entity_manager');
    }

    /**
     * Returns the instance related to the users instance.
     *
     * @return Instance
     */
    protected function getInstance()
    {
        return $this->container->get('celsius3_core.instance_helper')->getUrlInstance();
    }

    /**
     * Receive the confirmation token from user email provider, login the user.
     */
    public function confirmAction(Request $request, $token)
    {
        /** @var $userManager \FOS\UserBundle\Model\UserManagerInterface */
        $userManager = $this->get('fos_user.user_manager');

        $user = $userManager->findUserByConfirmationToken($token);

        if (null === $user) {
            throw Exception::create(Exception::NOT_FOUND, "The user with confirmation token does not exist");
        }

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

        $dispatcher->dispatch(FOSUserEvents::REGISTRATION_CONFIRMED, new FilterUserResponseEvent($user, $request, $response));
        $event_login = new InteractiveLoginEvent($request, $this->get('security.token_storage')->getToken());
        $dispatcher->dispatch(SecurityEvents::INTERACTIVE_LOGIN, $event_login);

        return $response;
    }
}
