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

namespace Celsius3\CoreBundle\Mailer;

use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Router;
use Symfony\Component\Routing\RouterInterface;
use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Mailer\Mailer as DefaultMailer;
use Celsius3\CoreBundle\Helper\ConfigurationHelper;
use Celsius3\CoreBundle\Helper\InstanceHelper;
use Celsius3\CoreBundle\Helper\MailerHelper;
use Celsius3\CoreBundle\Manager\MailManager;

class FosMailer extends DefaultMailer
{
    protected $request_stack;
    protected $mailerHelper;
    protected $instance;
    private $mailManager;

    public function __construct($mailer, RouterInterface $router, EngineInterface $templating, array $parameters, RequestStack $request_stack, InstanceHelper $instanceHelper, MailerHelper $mailerHelper, MailManager $mailManager, \Twig_Environment $twig)
    {
        $this->instance = $instanceHelper->getSessionOrUrlInstance();
        if (!is_null($this->instance)) {
            $transport = \Swift_SmtpTransport::newInstance($this->instance->get(ConfigurationHelper::CONF__SMTP_HOST)->getValue(), $this->instance->get(ConfigurationHelper::CONF__SMTP_PORT)->getValue(), $this->instance->get(ConfigurationHelper::CONF__SMTP_PROTOCOL)->getValue())
                    ->setUsername($this->instance->get(ConfigurationHelper::CONF__SMTP_USERNAME)->getValue())
                    ->setPassword($this->instance->get(ConfigurationHelper::CONF__SMTP_PASSWORD)->getValue())
            ;
            $instanceMailer = \Swift_Mailer::newInstance($transport);

            parent::__construct($instanceMailer, $router, $templating, $parameters);
        }
        $this->request_stack = $request_stack;
        $this->mailerHelper = $mailerHelper;
        $this->mailManager = $mailManager;
        $this->templating = $templating;
        $this->twig = $twig;
    }

    public function sendConfirmationEmailMessage(UserInterface $user)
    {
        if (!$this->instance->get('smtp_status')->getValue()) {
            return;
        }

        $signature = $user->getInstance()->get(ConfigurationHelper::CONF__MAIL_SIGNATURE)->getValue();
        $template = $this->mailManager->getTemplate('user_confirmation', $this->instance);

        $url = $this->router->generate('fos_user_registration_confirm', array(
            'token' => $user->getConfirmationToken(),
                ), UrlGeneratorInterface::ABSOLUTE_URL);

        $rendered = $this->twig->createTemplate($template->getText())->render(array(
                    'user' => $user,
                    'url' => $url,
                ))."\n".$signature;
        $rendered = html_entity_decode($template->getTitle()."\n".$rendered);
        $fromEmail = $this->instance->get(ConfigurationHelper::CONF__SMTP_USERNAME)->getValue();

        $this->sendEmailMessage($rendered, $fromEmail, $user->getEmail());
    }

    public function sendResettingEmailMessage(UserInterface $user)
    {
        if (!$this->instance->get('smtp_status')->getValue()) {
            return;
        }

        $signature = $user->getInstance()->get(ConfigurationHelper::CONF__MAIL_SIGNATURE)->getValue();
        $template = $this->mailManager->getTemplate('resetting', $this->instance);

        $url = $this->router->generate('fos_user_resetting_reset', array('token' => $user->getConfirmationToken()), Router::ABSOLUTE_URL);
        $rendered = $this->twig->createTemplate($template->getText())->render(array(
                    'user' => $user,
                    'url' => $url,
                ))."\n".$signature;
        $rendered = html_entity_decode($template->getTitle()."\n".$rendered);
        $fromEmail = $this->instance->get(ConfigurationHelper::CONF__SMTP_USERNAME)->getValue();

        $this->sendEmailMessage($rendered, $fromEmail, $user->getEmail());
    }

    /**
     * @param string $renderedTemplate
     * @param string $fromEmail
     * @param string $toEmail
     */
    protected function sendEmailMessage($renderedTemplate, $fromEmail, $toEmail)
    {
        // Render the email, use the first line as the subject, and the rest as the body
        $renderedLines = explode("\n", trim($renderedTemplate));
        $subject = $renderedLines[0];
        $body = implode("\n", array_slice($renderedLines, 1));

        $message = \Swift_Message::newInstance()
                ->setSubject($subject)
                ->setFrom($fromEmail)
                ->setTo($toEmail)
                ->setBody($body, 'text/html');

        $this->mailer->send($message);
    }
}
