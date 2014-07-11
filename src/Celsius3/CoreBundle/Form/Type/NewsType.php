<?php

namespace Celsius3\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Celsius3\CoreBundle\Document\Instance;

class NewsType extends AbstractType
{
    private $instance;

    public function __construct(Instance $instance)
    {
        $this->instance = $instance;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('title')
                ->add('text', 'textarea', array(
                    'attr' => array(
                        'class' => 'summernote',
                    ),
                ))
                ->add('date', 'datetime', array(
                    'widget' => 'single_text',
                    'format' => 'dd/MM/yyyy HH:mm',
                    'attr' => array(
                        'class' => 'news-date',
                    ),
                ))
                ->add('instance', 'celsius3_corebundle_instance_selector', array(
                    'data' => $this->instance,
                    'attr' => array(
                        'value' => $this->instance->getId(),
                        'readonly' => 'readonly',
                    ),
                ))
        ;
    }

    public function getName()
    {
        return 'celsius3_corebundle_newstype';
    }
}