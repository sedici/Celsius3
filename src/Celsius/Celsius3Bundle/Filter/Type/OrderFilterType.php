<?php

namespace Celsius\Celsius3Bundle\Filter\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class OrderFilterType extends AbstractType
{

    private $instance;
    private $owner;

    public function __construct($instance = null, $owner = null)
    {
        $this->instance = $instance;
        $this->owner = $owner;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (is_null($this->owner))
        {
            $builder->add('owner', 'document', array(
                'required' => false,
                'class' => 'CelsiusCelsius3Bundle:BaseUser',
            ));
        }

        $builder
                ->add('code', null, array(
                    'required' => false,
                ))
                ->add('type', 'choice', array(
                    'required' => false,
                    'choices' => array(
                        '' => '',
                        0 => 'Provision',
                        1 => 'Search',
                    ),
                ))
                ->add('state', 'choice', array(
                    'required' => false,
                    'choices' => array(
                        'created' => 'Created',
                        'searched' => 'Searched',
                        'requested' => 'Requested',
                        'received' => 'Received',
                        'delivered' => 'Delivered',
                        'canceled' => 'Canceled',
                        'annuled' => 'Annuled',
                    ),
                    'multiple' => true,
                    'expanded' => true,
                ))
        ;

        if (is_null($this->instance))
        {
            $builder->add('instance', 'document', array(
                'required' => false,
                'class' => 'CelsiusCelsius3Bundle:Instance',
            ));
        }
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'csrf_protection' => false,
        ));
    }

    public function getName()
    {
        return '';
    }

}
