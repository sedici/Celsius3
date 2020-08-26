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

declare(strict_types=1);

namespace Celsius3\CoreBundle\Mailer;

use Celsius3\CoreBundle\Helper\ConfigurationHelper;
use Celsius3\CoreBundle\Helper\InstanceHelper;
use Celsius3\CoreBundle\Helper\MailerHelper;
use Celsius3\CoreBundle\Manager\MailManager;
use FOS\UserBundle\Mailer\Mailer as DefaultMailer;
use FOS\UserBundle\Model\UserInterface;
use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\Router;
use Symfony\Component\Routing\RouterInterface;
use Twig\Environment;

use function array_slice;
use function compact;
use function html_entity_decode;

class FosMailer extends DefaultMailer
{
    protected $requestStack;
    protected $mailerHelper;
    protected $instance;
    protected $mailManager;
    protected $twig;

    public function __construct(
        RouterInterface $router,
        EngineInterface $templating,
        array $parameters,
        RequestStack $requestStack,
        InstanceHelper $instanceHelper,
        MailerHelper $mailerHelper,
        MailManager $mailManager,
        Environment $twig
    ) {
        $this->instance = $instanceHelper->getSessionOrUrlInstance();
        if ($this->instance !== null) {
            $transport = Swift_SmtpTransport::newInstance(
                $this->instance->get(ConfigurationHelper::CONF__SMTP_HOST)->getValue(),
                $this->instance->get(ConfigurationHelper::CONF__SMTP_PORT)->getValue(),
                $this->instance->get(ConfigurationHelper::CONF__SMTP_PROTOCOL)->getValue()
            )
                ->setUsername($this->instance->get(ConfigurationHelper::CONF__SMTP_USERNAME)->getValue())
                ->setPassword($this->instance->get(ConfigurationHelper::CONF__SMTP_PASSWORD)->getValue());
            $instance_mailer = Swift_Mailer::newInstance($transport);

            parent::__construct($instance_mailer, $router, $templating, $parameters);
        }
        $this->requestStack = $requestStack;
        $this->mailerHelper = $mailerHelper;
        $this->mailManager = $mailManager;
        $this->templating = $templating;
        $this->twig = $twig;
    }

    public function sendConfirmationEmailMessage(UserInterface $user): void
    {
        if (!$this->instance->get('smtp_status')->getValue()) {
            return;
        }

        $signature = $user->getInstance()->get(ConfigurationHelper::CONF__MAIL_SIGNATURE)->getValue();
        $template = $this->mailManager->getTemplate('user_confirmation', $this->instance);

        $url = $this->router->generate(
            'fos_user_registration_confirm',
            [
                'token' => $user->getConfirmationToken(),
            ],
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        $rendered = $this->twig->createTemplate($template->getText())->render(compact('user', 'url'))."\n".$signature;
        $rendered = html_entity_decode($template->getTitle()."\n".$rendered);
        $from_email = $this->instance->get(ConfigurationHelper::CONF__SMTP_USERNAME)->getValue();

        $this->sendEmailMessage($rendered, $from_email, $user->getEmail());
    }

    /**
     * @param  string  $renderedTemplate
     * @param  string  $fromEmail
     * @param  string  $toEmail
     */
    protected function sendEmailMessage($renderedTemplate, $fromEmail, $toEmail): void
    {
        // Render the email, use the first line as the subject, and the rest as the body
        $rendered_lines = explode("\n", trim($renderedTemplate));
        $subject = $rendered_lines[0];
        $body = implode("\n", array_slice($rendered_lines, 1));

        $message = Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom($fromEmail)
            ->setTo($toEmail)
            ->setBody($body, 'text/html');

        $this->mailer->send($message);
    }

    public function sendResettingEmailMessage(UserInterface $user): void
    {
        if (!$this->instance->get('smtp_status')->getValue()) {
            return;
        }

        $signature = $user->getInstance()->get(ConfigurationHelper::CONF__MAIL_SIGNATURE)->getValue();
        $template = $this->mailManager->getTemplate('resetting', $this->instance);

        $url = $this->router->generate(
            'fos_user_resetting_reset',
            ['token' => $user->getConfirmationToken()],
            Router::ABSOLUTE_URL
        );
        $rendered = $this->twig->createTemplate($template->getText())->render(compact('user', 'url'))."\n".$signature;
        $rendered = html_entity_decode($template->getTitle()."\n".$rendered);
        $from_email = $this->instance->get(ConfigurationHelper::CONF__SMTP_USERNAME)->getValue();

        $this->sendEmailMessage($rendered, $from_email, $user->getEmail());
    }
}
