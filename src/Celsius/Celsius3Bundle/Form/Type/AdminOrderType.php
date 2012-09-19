<?php

namespace Celsius\Celsius3Bundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class AdminOrderType extends OrderType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($builder->getData())
        {
            $owner = $builder->getData()->getOwner();
        } else
        {
            $owner = '';
        }

        $builder
                ->add('owner_autocomplete', 'text', array(
                    'attr' => array(
                        'class' => 'autocomplete',
                        'target' => 'BaseUser',
                        'value' => $owner,
                    ),
                    'mapped' => false,
                    'label' => 'Owner',
                ))
        ;

        parent::buildForm($builder, $options);
    }

    public function getName()
    {
        return 'celsius_celsius3bundle_ordertype';
    }

}