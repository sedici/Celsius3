<?php

namespace Celsius\Celsius3Bundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ConfirmationType extends AbstractType
{

    private $configurationHelper;

    public function __construct($configurationHelper)
    {
        $this->configurationHelper = $configurationHelper;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'choices' => $this->configurationHelper->confirmation,
            'required' => true,
            'expanded' => true,
        ));
    }

    public function getParent()
    {
        return 'choice';
    }

    public function getName()
    {
        return 'confirmation_type';
    }

}