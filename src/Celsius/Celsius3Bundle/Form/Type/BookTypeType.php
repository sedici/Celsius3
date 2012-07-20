<?php

namespace Celsius\Celsius3Bundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class BookTypeType extends MaterialTypeType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        parent::buildForm($builder, $options);
        
        $builder
            ->add('editor')
            ->add('chapter')
            ->add('isbn')
            ->add('withIndex')
        ;
    }
    
    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'Celsius\\Celsius3Bundle\\Document\\BookType',
        );
    }

    public function getName()
    {
        return 'celsius_celsius3bundle_booktype';
    }
}
