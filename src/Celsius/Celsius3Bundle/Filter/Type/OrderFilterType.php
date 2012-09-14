<?php

namespace Celsius\Celsius3Bundle\Filter\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class OrderFilterType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
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
                ->add('owner', 'document', array(
                    'required' => false,
                    'class' => 'CelsiusCelsius3Bundle:BaseUser',
                ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'csrf_protection' => false,
        ));
    }

    public function getName()
    {
        return 'celsius_celsius3bundle_orderfiltertype';
    }

}
