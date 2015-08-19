<?php

/*
 * Celsius3 - Order management
 * Copyright (C) 2014 PrEBi <info@prebi.unlp.edu.ar>
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

namespace Celsius3\CoreBundle\Mailer;

use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;
use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Mailer\Mailer as DefaultMailer;
use Celsius3\CoreBundle\Helper\ConfigurationHelper;
use Celsius3\CoreBundle\Helper\InstanceHelper;
use Celsius3\CoreBundle\Helper\MailerHelper;

class FosMailer extends DefaultMailer
{

    protected $request_stack;
    protected $mailerHelper;

    public function __construct($mailer, RouterInterface $router, EngineInterface $templating, array $parameters, RequestStack $request_stack, InstanceHelper $instanceHelper, MailerHelper $mailerHelper)
    {
        $instance = $instanceHelper->getSessionInstance();
        $transport = \Swift_SmtpTransport::newInstance($instance->get('smtp_host')->getValue(), $instance->get('smtp_port')->getValue())
                ->setUsername($instance->get('smtp_username')->getValue())
                ->setPassword($instance->get('smtp_password')->getValue())
        ;
        $instanceMailer = \Swift_Mailer::newInstance($transport);

        parent::__construct($instanceMailer, $router, $templating, $parameters);
        $this->request_stack = $request_stack;
        $this->mailerHelper = $mailerHelper;
    }

    public function sendConfirmationEmailMessage(UserInterface $user)
    {
        if (!$this->mailerHelper->validateSmtpServerData($instance)) {
            return;
        }

        $signature = $user->getInstance()->get(ConfigurationHelper::CONF__MAIL_SIGNATURE)->getValue();

        $template = $this->parameters['confirmation.template'];
        $url = $this->router->generate('fos_user_registration_confirm', array(
            'token' => $user->getConfirmationToken(),
            'url' => $this->request_stack->getCurrentRequest()->get('url'),
                ), true);
        $rendered = $this->templating->render($template, array(
                    'user' => $user,
                    'confirmationUrl' => $url,
                )) . "\n" . $signature;
        $this->sendEmailMessage($rendered, $this->parameters['from_email']['confirmation'], $user->getEmail());
    }

    public function sendResettingEmailMessage(UserInterface $user)
    {
        if (!$this->mailerHelper->validateSmtpServerData($instance)) {
            return;
        }

        $signature = $user->getInstance()->get(ConfigurationHelper::CONF__MAIL_SIGNATURE)->getValue();

        $template = $this->parameters['resetting.template'];
        $url = $this->router->generate('fos_user_resetting_reset', array(
            'token' => $user->getConfirmationToken(),
            'url' => $user->getInstance()->getUrl(),
                ), true);
        $rendered = $this->templating->render($template, array(
                    'user' => $user,
                    'confirmationUrl' => $url,
                )) . "\n" . $signature;
        $this->sendEmailMessage($rendered, $this->parameters['from_email']['resetting'], $user->getEmail());
    }

}
