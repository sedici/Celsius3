<?php

namespace Celsius\Celsius3Bundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class BookTypeType extends MaterialTypeType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
                ->add('editor')
                ->add('chapter')
                ->add('ISBN')
                ->add('withIndex')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Celsius\\Celsius3Bundle\\Document\\BookType',
        ));
    }

    public function getName()
    {
        return 'celsius_celsius3bundle_booktype';
    }

}
