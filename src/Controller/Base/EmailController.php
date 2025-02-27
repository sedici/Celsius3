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
use Celsius3\Entity\Instance;
use Celsius3\Helper\ConfigurationHelper;
use Celsius3\Manager\InstanceManager;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Celsius3\Helper\InstanceHelper;
use Celsius3\Helper\MailerHelper;
use Celsius3\Manager\FilterManager;
use Celsius3\Manager\UnionManager;
use Celsius3\Manager\UserManager;
use Celsius3\Repository\BaseUserRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Email as MimeEmail;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Constraints\{Email as EmailConstraint, NotBlank};
use Symfony\Component\Validator\Validator\ValidatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport;

abstract class EmailController extends EntityController
{

    public function __construct(
        protected MailerInterface $mailer,
        protected MailManager $mailManager,
        protected MailerHelper $mailerHelper,
        protected readonly LoggerInterface $logger,
        protected readonly VerifyEmailHelperInterface $verifyEmailHelper,
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
        SessionInterface $session,
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

        $this->setInstanceDependent(false);
        $this->setSortDefaults([
            'defaultSortFieldName' => 'e.createdAt',
            'defaultSortDirection' => 'desc',
        ]);

        $this->mailer = new Mailer(Transport::fromDsn(getenv('MAILER_DSN')));
    }


    public function listQuery(bool|null $isInstanceDependent = null): QueryBuilder
    {
        if (!$isInstanceDependent && !$this->isInstanceDependent)
            return parent::listQuery($isInstanceDependent);

        return $this->repository->createQueryBuilder('e')
            ->andWhere('e.instance = :instance_id')
            ->setParameter('instance_id', $this->instance->getId());
    }


    // public function sendInstanceEmails(
    //     OutputInterface $output, int $limit, int $logLevel = 2
    // ): void {
    //     if (!$this->mailerHelper->validateSmtpServerData($this->instance)) {
    //         if ($logLevel <= 2) {
    //             $message = 'Instance ' . $this->instance->getUrl() . ': The SMTP server data are not valid.';
    //             $output?->writeln($message);
    //             $this->logger->error($message);
    //         }
    //         return;
    //     }

    //     $emails = $this->entityManager->getRepository(Email::class)
    //         ->findNotSentEmailsWithLimit($this->instance, $limit);

    //     if (count($emails) === 0) {
    //         $this->addFlash('info', 'No emails to send.');
    //         return;
    //     }

    //     $signature = $this->instance->get($this->configurationHelper::CONF__MAIL_SIGNATURE)->getValue();
    //     $smtpUsername = $this->instance->get($this->configurationHelper::CONF__SMTP_USERNAME)->getValue();

    //     if ($logLevel <= 3) {
    //         $message = 'Sending mails from instance ' . $this->instance->getUrl();
    //         $output?->writeln($message);
    //         $this->logger->info($message);
    //     }

    //     foreach ($emails as $email) {
    //         try {
    //             if ($email->getAttempts() < 10 || $email->getUpdatedAt()->diff(new \DateTime())->h > 2) {
    //                 $from = $smtpUsername;
    //                 if ($logLevel <= 2) {
    //                     $message = 'Sending mail from ' . $from . ' to ' . $email->getAddress();
    //                     $output?->writeln($message);
    //                     $this->logger->info($message);
    //                 }

    //                 $emailMessage = (new MimeEmail())
    //                     ->subject($email->getSubject())
    //                     ->from($from)
    //                     ->to($email->getAddress())
    //                     ->html($email->getText() . "\n" . $signature);

    //                 $this->mailer->send($emailMessage);

    //                 $email->setSent(true);
    //                 $this->persistEntity($email);
    //             }
    //         } catch (TransportExceptionInterface $e) {
    //             $email->addAttempt();

    //             $diff = $email->getCreatedAt()->diff(new \DateTime());
    //             $hours = $diff->h + ($diff->days * 24);
    //             if ($hours > 48) {
    //                 $email->setError(true);
    //             }

    //             $this->entityManager->persist($email);
    //             $this->entityManager->flush();

    //             $message = "Error al enviar el correo con ID: " . $email->getId();
    //             $this->logger->error($message, ['exception' => $e]);
    //             $output?->writeln($message);
    //         }
    //     }
    // }


    public function sendInstanceEmails(
        OutputInterface $output, int $limit, int $logLevel = 2
    ): void {
        $emails = $this->entityManager->getRepository(Email::class)
            ->findNotSentEmailsWithLimit($this->instance, $limit);
    
        foreach ($emails as $email) {
            $success = $this->sendMimeEmail(
                $email->getAddress(),
                $email->getSubject(),
                $email->getText()
            );
    
            if ($success) $email->setSent(true);
            else $email->addAttempt();
    
            $this->entityManager->flush();
        }
    }


    public function sendConfirmationEmailMessage(BaseUser $user): void
    { $this->sendTemplatedEmail($user, 'user_confirmation', 'fos_user_registration_confirm'); }


    public function sendResettingEmailMessage(BaseUser $user): void
    { $this->sendTemplatedEmail($user, 'resetting', 'fos_user_resetting_reset'); }


    protected function sendTemplatedEmail(BaseUser $user, string $templateName, string $routeName): void
    {
        if (!$this->instance->get('smtp_status')->getValue()) return;

        $signature = $this->instance->get($this->configurationHelper::CONF__MAIL_SIGNATURE)->getValue();
        $template = $this->mailManager->getTemplate($templateName, $this->instance);

        $url = $this->router->generate(
            $routeName,
            ['token' => $user->getConfirmationToken()],
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        $rendered = $this->htmlRenderer
            ->createTemplate($template->getText())
            ->render(['user' => $user, 'url' => $url]) . "\n" . $signature;
        $rendered = html_entity_decode($template->getTitle() . "\n" . $rendered);
        $fromEmail = $this->instance->get($this->configurationHelper::CONF__SMTP_USERNAME)->getValue();

        $this->sendEmailMessage($rendered, $fromEmail, $user->getEmail());
    }


    protected function sendEmailMessage(string $renderedTemplate, string $fromEmail, string $toEmail): void
    {
        $renderedLines = explode("\n", trim($renderedTemplate));
        $subject = array_shift($renderedLines);
        $body = implode("\n", $renderedLines);

        $this->sendEmail($toEmail, $subject, $body);
    }


    public function sendVerificationEmail(
        BaseUser $user,
        string $verifyEmailRouteName,
        string $templatePath,
        array $context = []
    ): void {
        $fromEmail = $this->instance->get(
            $this->configurationHelper::CONF__SMTP_USERNAME
        )->getValue();

        $signatureComponents = $this->verifyEmailHelper->generateSignature(
            $verifyEmailRouteName,
            $user->getId(),
            $user->getEmail()
        );

        $context['signedUrl'] = $signatureComponents->getSignedUrl();
        $context['expiresAtMessageKey'] = $signatureComponents->getExpirationMessageKey();
        $context['expiresAtMessageData'] = $signatureComponents->getExpirationMessageData();

        $email = (new TemplatedEmail())
            ->from(new Address($fromEmail))
            ->to($user->getEmail())
            ->htmlTemplate($templatePath)
            ->context($context);

        try {
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $e) {
            $this->logger->error('Error sending verification email: ' . $e->getMessage(), ['exception' => $e]);
            throw new \RuntimeException('Error sending verification email: ' . $e->getMessage(), 0, $e);
        }
    }


    #[
        Route('/public/verify/email', name: 'verify_email'),
        IsGranted('IS_AUTHENTICATED_FULLY')
    ]
    public function verifyUserEmail(): Response
    {
        $request = $this->requestStack->getCurrentRequest();
        $user = $this->getUser();

        try {
            $this->verifyEmailHelper->validateEmailConfirmationFromRequest(
                $request,
                $user->getId(),
                $user->getEmail()
            );
        } catch (VerifyEmailExceptionInterface $e) {
            $this->addFlash('verify_email_error', $e->getReason());

            return $this->redirectToRoute('app_register');
        }

        $user->setIsVerified(true);

        $baseUserRepository = $this->entityManager->getRepository(BaseUser::class);
        $baseUserRepository->persist($user);
        $baseUserRepository->flush();

        $this->addFlash('success', 'Your email address has been verified.');

        return $this->redirectToRoute('app_home');
    }


    public function checkAddress(string $address): bool
    {
        $errors = $this->validator->validate(
            $address, [new EmailConstraint(), new NotBlank()]
        );

        return count($errors) === 0;
    }


    public function saveEntityEmail(
        string $address,
        string $subject,
        string $text,
        ?BaseUser $sender = null,
        ?Instance $instance = null,
        ?Email $email = null
    ): ?Email {
        $instance ??= $this->instance;
        $sender ??= $this->getUser();
        $email ??= new Email();

        $email = $email
            ->setAddress($address)
            ->setSubject($subject)
            ->setText($text)
            ->setSent(true)
            ->setInstance($instance)
            ->setSender($sender);
            
        $this->persistEntity($email);

        return $email;
    }

    public function sendMimeEmail(
        string $address,
        string $subject,
        string $text,
        ?BaseUser $sender = null,
        ?MimeEmail $email = null,
        ?MailerInterface $mailer = null
    ): bool {
        $email ??= new MimeEmail();
        $mailer ??= $this->mailer;
        $sender ??= $this->getUser();

        try {
            $email = $email
                ->from(new Address($sender->getEmail()))
                ->to(new Address($address))
                ->subject($subject)
                ->text($text);

            $this->mailer->send($email);
            return true;
        } catch (Exception $e) {
            // $celsiusEmail->setError(true);
            // $this->entityManager->flush();
            $this->logger->error('Error sending email: ' . $e->getMessage(), ['exception' => $e]);
            return false;
        }
    }


    public function sendEmail(
        string $address,
        string $subject,
        string $text,
        ?BaseUser $sender = null,
        ?Instance $instance = null
    ): bool {
        if (!$this->checkAddress($address)) return false;

        $sender ??= $this->getUser();
        $instance ??= $this->instance;

        $celsiusEmail = (new Email())->incrementAttempts();

        $success = $this->sendMimeEmail($address, $subject, $text);

        if (!$success) return false;

        $celsiusEmail = $this->saveEntityEmail(
            $address, $subject, $text, email: $celsiusEmail
        );

        return true;
    }


    public function validateSmtpServerData(Instance $instance)
    {
        $testResult = $this->testConnection(
            $instance->get('smtp_host')->getValue(),
            $instance->get('smtp_port')->getValue(),
            $instance->get('smtp_protocol')->getValue(),
            $instance->get('smtp_username')->getValue(),
            $instance->get('smtp_password')->getValue()
        );

        return $testResult['test'];
    }


    public function testConnection($host, $port, $protocol, $user, $pass): array
    {
        try {
            $dsn = sprintf('%s://%s:%s@%s:%s',
                $protocol === 'ssl' ? 'smtps' : 'smtp',
                $user, $pass, $host, $port
            );

            $transport = Transport::fromDsn($dsn);
            $mailer = new Mailer($transport);

            $this->sendMimeEmail(
                'test@example.com',
                'Test Connection',
                'This is a test email to verify the connection.',
                $this->getUser(),
                mailer: $mailer
            );

            return [ 'test' => true, 'message' => $this->translator->trans('Sucefull connection') ];
        } catch (TransportExceptionInterface $e) {
            return [ 'test' => false, 'message' => $e->getMessage() ];
        } catch (Exception $e) {
            return [ 'test' => false, 'message' => $e->getMessage() ];
        }
    }
}