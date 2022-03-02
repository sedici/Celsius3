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

namespace Celsius3\EventListener;

//use FOS\UserBundle\FOSUserEvents;
//use FOS\UserBundle\Event\FormEvent;
//use FOS\UserBundle\Mailer\MailerInterface;
//use FOS\UserBundle\Util\TokenGeneratorInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Celsius3\Helper\ConfigurationHelper;

class ConfirmationListener //implements EventSubscriberInterface
{
//
//    private $mailer;
//    private $tokenGenerator;
//    private $router;
//    private $session;
//    private $configuration_helper;
//
//    public function __construct(MailerInterface $mailer, TokenGeneratorInterface $tokenGenerator, UrlGeneratorInterface $router, SessionInterface $session, ConfigurationHelper $configuration_helper)
//    {
//        $this->mailer = $mailer;
//        $this->tokenGenerator = $tokenGenerator;
//        $this->router = $router;
//        $this->session = $session;
//        $this->configuration_helper = $configuration_helper;
//    }
//
//    public static function getSubscribedEvents()
//    {
//        return array(
//            FOSUserEvents::REGISTRATION_SUCCESS => 'onRegistrationSuccess',
//        );
//    }
//
//    public function onRegistrationSuccess(FormEvent $event)
//    {
//        /** @var $user \FOS\UserBundle\Model\UserInterface */
//        $user = $event->getForm()->getData();
//
//        $user->setEnabled(false);
//
//        $confirmationType = $this->configuration_helper->getCastedValue($user->getInstance()->get('confirmation_type'));
//
//        $this->session->set('fos_user_send_confirmation_email/email', $user->getEmail());
//
//        if ($confirmationType == 'email') {
//            if (null === $user->getConfirmationToken()) {
//                $user->setConfirmationToken($this->tokenGenerator->generateToken());
//            }
//
//            $this->mailer->sendConfirmationEmailMessage($user);
//
//            $url = $this->router->generate('fos_user_registration_check_email');
//        } elseif ($confirmationType == 'admin') {
//            $url = $this->router->generate('registration_wait_confirmation');
//        }
//
//        $event->setResponse(new RedirectResponse($url));
//    }

}
