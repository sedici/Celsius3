<?php

namespace Celsius\Celsius3Bundle\EventListener;

use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Mailer\MailerInterface;
use FOS\UserBundle\Util\TokenGeneratorInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Celsius\Celsius3Bundle\Helper\ConfigurationHelper;

class ConfirmationListener implements EventSubscriberInterface
{

    private $mailer;
    private $tokenGenerator;
    private $router;
    private $session;
    private $request;
    private $configuration_helper;

    public function __construct(MailerInterface $mailer, TokenGeneratorInterface $tokenGenerator, UrlGeneratorInterface $router, SessionInterface $session, Request $request, ConfigurationHelper $configuration_helper)
    {
        $this->mailer = $mailer;
        $this->tokenGenerator = $tokenGenerator;
        $this->router = $router;
        $this->session = $session;
        $this->request = $request;
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

        if ($confirmationType == 'email')
        {
            if (null === $user->getConfirmationToken())
            {
                $user->setConfirmationToken($this->tokenGenerator->generateToken());
            }

            $this->mailer->sendConfirmationEmailMessage($user);

            $url = $this->router->generate('fos_user_registration_check_email', array('url' => $this->request->get('url')));
        } else if ($confirmationType == 'admin')
        {
            $url = $this->router->generate('fos_user_registration_wait_confirmation', array('url' => $this->request->get('url')));
        }

        $event->setResponse(new RedirectResponse($url));
    }

}
