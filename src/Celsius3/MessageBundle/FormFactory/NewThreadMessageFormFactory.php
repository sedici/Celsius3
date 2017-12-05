<?php

namespace Celsius3\MessageBundle\FormFactory;

use FOS\MessageBundle\FormFactory\AbstractMessageFormFactory;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

/**
 * Instanciates message forms.
 *
 * @author Thibault Duplessis <thibault.duplessis@gmail.com>
 */
class NewThreadMessageFormFactory extends AbstractMessageFormFactory
{
    public function __construct(FormFactoryInterface $formFactory, $formType, $formName, $messageClass)
    {
        parent::__construct($formFactory, $formType, $formName, $messageClass);
    }

    /**
     * Creates a new thread message.
     *
     * @return FormInterface
     */
    public function create()
    {
        return $this->formFactory->createNamed($this->formName, get_class($this->formType), $this->createModelInstance());
    }
}
