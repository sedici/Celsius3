<?php

namespace Celsius\Celsius3Bundle\Form\Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Celsius\Celsius3Bundle\Form\EventListener\AddInstitutionFieldsSubscriber;

class OrderReclaimType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('observations', 'textarea', array('required' => false,));
    }

    public function getName()
    {
        return 'celsius_celsius3bundle_orderreclaimtype';
    }

}
