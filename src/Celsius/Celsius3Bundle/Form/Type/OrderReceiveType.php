<?php

namespace Celsius\Celsius3Bundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Celsius\Celsius3Bundle\Form\EventListener\AddInstitutionFieldsSubscriber;

class OrderReceiveType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('deliverytype', 'choice', array(
                    'choices' => array(
                        'PDF',
                        'Printed',
                    ),
                    'label' => 'Delivery Type'
                ))
                ->add('observations', 'textarea', array(
                    'required' => false,
                ))
                ->add('files', 'collection', array(
                    'label' => 'Files',
                    'type' => 'file',
                    'prototype' => true,
                    'allow_add' => true,
                    'allow_delete' => true,
                ))
        ;
    }

    public function getName()
    {
        return 'celsius_celsius3bundle_orderreceivetype';
    }

}