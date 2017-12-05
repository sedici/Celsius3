<?php

namespace Celsius3\MessageBundle\FormFactory;

use FOS\MessageBundle\FormFactory\AbstractMessageFormFactory;
use FOS\MessageBundle\Model\ThreadInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

/**
 * Instanciates message forms.
 *
 * @author Thibault Duplessis <thibault.duplessis@gmail.com>
 */
class ReplyMessageFormFactory extends AbstractMessageFormFactory
{
    public function __construct(FormFactoryInterface $formFactory, $formType, $formName, $messageClass)
    {
        parent::__construct($formFactory, $formType, $formName, $messageClass);
    }

    /**
     * Creates a reply message.
     *
     * @param ThreadInterface $thread the thread we answer to
     *
     * @return FormInterface
     */
    public function create(ThreadInterface $thread)
    {
        $message = $this->createModelInstance();
        $message->setThread($thread);

        return $this->formFactory->createNamed($this->formName, get_class($this->formType), $message);
    }
}
