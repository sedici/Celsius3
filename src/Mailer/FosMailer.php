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

namespace Celsius3\Mailer;

use Celsius3\Entity\Instance;
use Celsius3\Helper\ConfigurationHelper;
use Celsius3\Helper\InstanceHelper;
use Celsius3\Manager\MailManager;
// use FOS\UserBundle\Model\UserInterface;
use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\Router;
use Symfony\Component\Routing\RouterInterface;
use Twig\Environment;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;

use function array_slice;
use function compact;
use function html_entity_decode;

class FosMailer extends Mailer //extends DefaultMailer
{
    protected RequestStack $requestStack;
    protected Instance $instance;
    protected Environment $twig;
    protected RouterInterface $router;
    protected MailManager $mailManager;
    protected MailerInterface $mailer;

    public function __construct(
        RouterInterface $router,
        RequestStack $requestStack,
        InstanceHelper $instanceHelper,
        Environment $twig,
        MailManager $mailManager,
        MailerInterface $mailer,
        ... $args
    ) {
        parent::__construct(... $args);
        $this->instance = $instanceHelper->getSessionOrUrlInstance();
        $this->requestStack = $requestStack;
        $this->twig = $twig;
        $this->router = $router;
        $this->mailManager = $mailManager;
        $this->mailer = $mailer;
    }

    public function sendConfirmationEmailMessage($user): void
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

        // Renderizar el contenido del email
        $rendered = $this->twig->createTemplate($template->getText())->render(compact('user', 'url')) . "\n" . $signature;
        $rendered = html_entity_decode($template->getTitle() . "\n" . $rendered);
        $fromEmail = $this->instance->get(ConfigurationHelper::CONF__SMTP_USERNAME)->getValue();

        // Enviar el email
        $this->sendEmailMessage($rendered, $fromEmail, $user->getEmail());
    }


    protected function sendEmailMessage(
        string $renderedTemplate,
        string $fromEmail,
        string $toEmail
    ): void {
        // Renderizar el email, usar la primera línea como asunto y el resto como cuerpo
        $renderedLines = explode("\n", trim($renderedTemplate));
        $subject = array_shift($renderedLines); // Obtener el primer elemento como asunto
        $body = implode("\n", $renderedLines); // El resto es el cuerpo

        // Crear el mensaje usando Symfony Mailer
        $email = (new Email())
            ->from($fromEmail)
            ->to($toEmail)
            ->subject($subject)
            ->html($body); // Establecer el cuerpo como HTML

        // Enviar el mensaje
        try {
            $this->mailer->send($email);
        } catch (\Exception $e) {
            // Manejo de excepciones si es necesario
            // Puedes loggear el error o manejarlo según tus necesidades
            throw new \RuntimeException('Error sending email: ' . $e->getMessage());
        }
    }


    public function sendResettingEmailMessage($user): void
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
