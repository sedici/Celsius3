<?php

namespace Celsius\Celsius3Bundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AdminOrderType extends OrderType
{

    protected $operator;

    public function __construct($instance = null, $material = null, $user = null, $operator = null)
    {
        parent::__construct($instance, $material, $user);
        $this->operator = $operator;
    }

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
                ->add('operator', 'user_selector', array(
                    'attr' => array(
                        'value' => (!is_null($this->operator)) ? $this->operator->getId() : '',
                        'class' => 'container',
                        'readonly' => 'readonly',
                    ),
                ))
        ;

        parent::buildForm($builder, $options);
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
                'validation_groups' => array('newOrder'),
        ));
    }

    public function getName()
    {
        return 'celsius_celsius3bundle_ordertype';
    }

}