<?php

namespace Celsius\Celsius3Bundle\Handler;

use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Form\Handler\RegistrationFormHandler as RegistrationFormHandlerDefault;

class RegistrationFormHandler extends RegistrationFormHandlerDefault
{

    /**
     * @param boolean $confirmation
     */
    protected function onSuccess(UserInterface $user, $confirmation)
    {
        $user->setEnabled(false);
        if ($confirmation)
        {
            if (null === $user->getConfirmationToken())
            {
                $user->setConfirmationToken($this->tokenGenerator->generateToken());
            }

            $this->mailer->sendConfirmationEmailMessage($user);
        }

        $this->userManager->updateUser($user);
    }

}
