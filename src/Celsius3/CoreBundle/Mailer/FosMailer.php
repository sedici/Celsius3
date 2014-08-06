<?php

namespace Celsius3\CoreBundle\Mailer;

use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;
use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Mailer\Mailer as DefaultMailer;

class FosMailer extends DefaultMailer
{
    protected $request_stack;

    public function __construct($mailer, RouterInterface $router, EngineInterface $templating, array $parameters, RequestStack $request_stack)
    {
        parent::__construct($mailer, $router, $templating, $parameters);
        $this->request_stack = $request_stack;
    }

    public function sendConfirmationEmailMessage(UserInterface $user)
    {
        $template = $this->parameters['confirmation.template'];
        $url = $this->router->generate('fos_user_registration_confirm', array(
            'token' => $user->getConfirmationToken(),
            'url' => $this->request_stack->getCurrentRequest()->get('url'),
                ), true);
        $rendered = $this->templating->render($template, array(
            'user' => $user,
            'confirmationUrl' => $url,
        ));
        $this->sendEmailMessage($rendered, $this->parameters['from_email']['confirmation'], $user->getEmail());
    }

    public function sendResettingEmailMessage(UserInterface $user)
    {   
        $template = $this->parameters['resetting.template'];
        $url = $this->router->generate('fos_user_resetting_reset', array(
            'token' => $user->getConfirmationToken(),
            'url' => $user->getInstance()->getUrl(),
                ), true);
        $rendered = $this->templating->render($template, array(
            'user' => $user,
            'confirmationUrl' => $url,
        ));
        $this->sendEmailMessage($rendered, $this->parameters['from_email']['resetting'], $user->getEmail());
    }
}