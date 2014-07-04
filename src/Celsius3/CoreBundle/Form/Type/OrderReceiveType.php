<?php

namespace Celsius3\CoreBundle\Form\Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class OrderReceiveType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('deliverytype', 'choice',
                        array('choices' => array('PDF'=>'PDF', 'Printed' => 'Printed'),
                                'label' => 'Delivery Type'))
                ->add('observations', 'textarea', array('required' => false,))
                ->add('files', 'collection',
                        array('label' => 'Files', 'type' => 'file',
                                'prototype' => true, 'allow_add' => true,
                                'allow_delete' => true,));
    }

    public function getName()
    {
        return 'celsius3_corebundle_orderreceivetype';
    }

}
