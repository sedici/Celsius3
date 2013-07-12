<?php

namespace Celsius3\CoreBundle\Form\Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Celsius3\CoreBundle\Document\Instance;

class MailTemplateType extends AbstractType
{
    protected $instance;

    public function __construct(Instance $instance = null)
    {
        $this->instance = $instance;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title')->add('code')
                //    ->add('state', 'choice', array(
                //            'choices'   => array(1 => 'enabled', 0 => 'blocked'),
                //            'expanded'  => true,
                //            'multiple'  => false,
                //            'required'  => true,
                //        ))
                ->add('text', 'textarea',
                        array('attr' => array('class' => 'tinymce'),
                              'data' => $this->instance->get('mail_signature')->getValue()));
        if (!is_null($this->instance)) {
            $builder
                    ->add('instance', 'celsius3_corebundle_instance_selector',
                            array('data' => $this->instance,
                                    'attr' => array(
                                            'value' => $this->instance->getId(),
                                            'readonly' => 'readonly',),));
        }
    }

    public function getName()
    {
        return 'celsius3_corebundle_mailtemplatetype';
    }

}
