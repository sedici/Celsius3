<?php

namespace Celsius3\CoreBundle\Form\Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Celsius3\CoreBundle\Form\EventListener\AddInstitutionFieldsSubscriber;

class OrderReclaimType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('observations', 'textarea', array('required' => false,));
    }

    public function getName()
    {
        return 'celsius3_corebundle_orderreclaimtype';
    }

}
