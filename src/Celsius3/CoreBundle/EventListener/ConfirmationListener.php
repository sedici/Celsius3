<?php

namespace Celsius3\CoreBundle\EventListener;

use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Mailer\MailerInterface;
use FOS\UserBundle\Util\TokenGeneratorInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Celsius3\CoreBundle\Helper\ConfigurationHelper;

class ConfirmationListener implements EventSubscriberInterface
{

    private $mailer;
    private $tokenGenerator;
    private $router;
    private $session;
    private $request_stack;
    private $configuration_helper;

    public function __construct(MailerInterface $mailer, TokenGeneratorInterface $tokenGenerator, UrlGeneratorInterface $router, SessionInterface $session, RequestStack $request_stack, ConfigurationHelper $configuration_helper)
    {
        $this->mailer = $mailer;
        $this->tokenGenerator = $tokenGenerator;
        $this->router = $router;
        $this->session = $session;
        $this->request_stack = $request_stack;
        $this->configuration_helper = $configuration_helper;
    }

    public static function getSubscribedEvents()
    {
        return array(
            FOSUserEvents::REGISTRATION_SUCCESS => 'onRegistrationSuccess',
        );
    }

    public function onRegistrationSuccess(FormEvent $event)
    {
        /** @var $user \FOS\UserBundle\Model\UserInterface */
        $user = $event->getForm()->getData();

        $user->setEnabled(false);

        $confirmationType = $this->configuration_helper->getCastedValue($user->getInstance()->get('confirmation_type'));

        $this->session->set('fos_user_send_confirmation_email/email', $user->getEmail());

        if ($confirmationType == 'email') {
            if (null === $user->getConfirmationToken()) {
                $user->setConfirmationToken($this->tokenGenerator->generateToken());
            }

            $this->mailer->sendConfirmationEmailMessage($user);

            $url = $this->router->generate('fos_user_registration_check_email', array('url' => $this->request_stack->getCurrentRequest()->get('url')));
        } elseif ($confirmationType == 'admin') {
            $url = $this->router->generate('fos_user_registration_wait_confirmation', array('url' => $this->request_stack->getCurrentRequest()->get('url')));
        }

        $event->setResponse(new RedirectResponse($url));
    }

}
