<?php

namespace Celsius\Celsius3Bundle\Form\Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class NewsType extends AbstractType
{

    protected $instance;

    public function __construct($instance = null)
    {
        $this->instance = $instance;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title')
                ->add('text', 'textarea',
                        array('attr' => array('class' => 'tinymce',),))
                ->add('date', 'datetime',
                        array('widget' => 'single_text',
                                'format' => 'dd/MM/yyyy HH:mm',
                                'attr' => array('class' => 'news-date',),));
        if (!is_null($this->instance)) {
            $builder
                    ->add('instance', 'instance_selector',
                            array('data' => $this->instance,
                                    'attr' => array(
                                            'value' => $this->instance->getId(),
                                            'readonly' => 'readonly',),));
        }
    }

    public function getName()
    {
        return 'celsius_celsius3bundle_newstype';
    }

}
