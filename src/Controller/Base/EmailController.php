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

namespace Celsius3\Controller\Base;

use Celsius3\Controller\Core\EntityController;
use Celsius3\Entity\Email;
use Celsius3\Manager\MailManager;

use Celsius3\Controller\Core\HtmlRenderer;
use Celsius3\Controller\Core\RestRenderer;
use Celsius3\Entity\BaseUser;
use Celsius3\Helper\ConfigurationHelper;
use Celsius3\Manager\InstanceManager;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Celsius3\Helper\InstanceHelper;
use Celsius3\Helper\MailerHelper;
use Celsius3\Manager\FilterManager;
use Celsius3\Manager\UnionManager;
use Celsius3\Manager\UserManager;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;
use Symfony\Component\Mime\Email as MimeEmail;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Constraints\{Email as EmailConstraint, NotBlank};
use Symfony\Component\Validator\Validator\ValidatorInterface;


abstract class EmailController extends EntityController
{

    public function __construct(
        protected MailerInterface $mailer,
        protected MailManager $mailManager,
        protected MailerHelper $mailerHelper,
        protected readonly LoggerInterface $logger,
        ValidatorInterface $validator,
        InstanceManager $instanceManager,
        EntityManagerInterface $entityManager,
        PaginatorInterface $paginator,
        ConfigurationHelper $configurationHelper,
        TranslatorInterface $translator,
        ManagerRegistry $managerRegistry,
        RequestStack $requestStack,
        UnionManager $unionManager,
        UserManager $userManager,
        FilterManager $filterManager,
        InstanceHelper $instanceHelper,
        FormFactoryInterface $formFactory,
        FlashBagInterface $session,
        RouterInterface $router,
        TokenStorageInterface $tokenStorage,
        Security $security,
        HtmlRenderer $htmlRenderer,
        RestRenderer $restRenderer
    ) {
        parent::__construct(
            $validator,
            $instanceManager,
            $entityManager,
            $paginator,
            $configurationHelper,
            $translator,
            $managerRegistry,
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
        $this->setEntity(Email::class);
        parent::initialize();
        $this->setInstanceDependent(true);
        $this->setSortDefaults([
            'defaultSortFieldName' => 'e.name',
            'defaultSortDirection' => 'asc',
        ]);
    }


    public function listQuery(?bool $isInstanceDependent = null): QueryBuilder
    {
        return $this->entityManager
            ->getRepository($this->entityClassName)
            ->createQueryBuilder('e')
            ->andWhere('e.instance = :instance_id')
            ->andWhere('e.sender = :user_id')
            ->setParameter(
                'user_id',
                $this->getUser()->getId()
            )
            ->setParameter(
                'instance_id',
                $this->instance->getId()
            );
    }


    public function saveEmail(
        string $address,
        string $subject,
        string $text
    ): void {
        $email = (new Email())
            ->setAddress($address)
            ->setSubject($subject)
            ->setText($text);

        $token = $this->tokenStorage->getToken();
        $user = $token ? $token->getUser() : null;

        if ($user instanceof BaseUser) {
            $email->setSender($user);
        }

        $email->setInstance($this->instance);
        $email->setSent(false);

        $this->persistEntity($email);
    }


    public function sendEmail(string $address, string $subject, string $text): bool
    {
        $errors = $this->validator->validate($address, [new EmailConstraint(), new NotBlank()]);

        if (count($errors) > 0) {
            return false;
        }

        $token = $this->tokenStorage->getToken();
        $user = $token ? $token->getUser() : null;

        if ($user instanceof BaseUser) {
            $this->saveEmail($address, $subject, $text);
            return true;
        }

        return false;
    }


    public function sendInstanceEmails(int $limit, int $logLevel = 2, OutputInterface $output): void
    {
        if (!$this->mailerHelper->validateSmtpServerData($this->instance)) {
            if ($logLevel <= 2) {
                $message = 'Instance ' . $this->instance->getUrl() . ': The SMTP server data are not valid.';
                $output?->writeln($message);
                $this->logger->error($message);
            }
            return;
        }

        $emails = $this->entityManager->getRepository(Email::class)
            ->findNotSentEmailsWithLimit($this->instance, $limit);

        if (count($emails) === 0) {
            return;
        }

        $signature = $this->instance->get($this->configurationHelper::CONF__MAIL_SIGNATURE)->getValue();
        $smtpUsername = $this->instance->get($this->configurationHelper::CONF__SMTP_USERNAME)->getValue();

        if ($logLevel <= 3) {
            $message = 'Sending mails from instance ' . $this->instance->getUrl();
            $output?->writeln($message);
            $this->logger->info($message);
        }

        foreach ($emails as $email) {
            try {
                if ($email->getAttempts() < 10 || $email->getUpdatedAt()->diff(new \DateTime())->h > 2) {
                    $from = $smtpUsername;
                    if ($logLevel <= 2) {
                        $message = 'Sending mail from ' . $from . ' to ' . $email->getAddress();
                        $output?->writeln($message);
                        $this->logger->info($message);
                    }

                    $emailMessage = (new MimeEmail())
                        ->subject($email->getSubject())
                        ->from($from)
                        ->to($email->getAddress())
                        ->html($email->getText() . "\n" . $signature);

                    $this->mailer->send($emailMessage);

                    $email->setSent(true);
                    $this->persistEntity($email);
                }
            } catch (TransportExceptionInterface $e) {
                $email->addAttempt();

                $diff = $email->getCreatedAt()->diff(new \DateTime());
                $hours = $diff->h + ($diff->days * 24);
                if ($hours > 48) {
                    $email->setError(true);
                }

                $this->entityManager->persist($email);
                $this->entityManager->flush();

                $message = "Error al enviar el correo con ID: " . $email->getId();
                $this->logger->error($message, ['exception' => $e]);
                $output?->writeln($message);
            }
        }
    }


    public function sendConfirmationEmailMessage(BaseUser $user): void
    { $this->sendTemplatedEmail($user, 'user_confirmation', 'fos_user_registration_confirm'); }


    public function sendResettingEmailMessage(BaseUser $user): void
    { $this->sendTemplatedEmail($user, 'resetting', 'fos_user_resetting_reset'); }


    private function sendTemplatedEmail(BaseUser $user, string $templateName, string $routeName): void
    {
        if (!$this->instance->get('smtp_status')->getValue()) {
            return;
        }

        $signature = $this->instance->get($this->configurationHelper::CONF__MAIL_SIGNATURE)->getValue();
        $template = $this->mailManager->getTemplate($templateName, $this->instance);

        $url = $this->router->generate(
            $routeName,
            ['token' => $user->getConfirmationToken()],
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        // $rendered = $this->twig->createTemplate($template->getText())->render(['user' => $user, 'url' => $url]) . "\n" . $signature;
        // $rendered = html_entity_decode($template->getTitle() . "\n" . $rendered);
        // $fromEmail = $this->instance->get($this->configurationHelper::CONF__SMTP_USERNAME)->getValue();

        // $this->sendEmailMessage($rendered, $fromEmail, $user->getEmail());
    }


    private function sendEmailMessage(string $renderedTemplate, string $fromEmail, string $toEmail): void
    {
        $renderedLines = explode("\n", trim($renderedTemplate));
        $subject = array_shift($renderedLines);
        $body = implode("\n", $renderedLines);

        $email = (new MimeEmail())
            ->from($fromEmail)
            ->to($toEmail)
            ->subject($subject)
            ->html($body);

        try {
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $e) {
            $this->logger->error('Error sending email: ' . $e->getMessage(), ['exception' => $e]);
            throw new \RuntimeException('Error sending email: ' . $e->getMessage(), 0, $e);
        }
    }
}