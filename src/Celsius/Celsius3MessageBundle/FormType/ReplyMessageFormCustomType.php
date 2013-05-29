<?php

namespace Celsius\Celsius3MessageBundle\FormType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ReplyMessageFormCustomType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('body', 'textarea',
                        array('attr' => array('class' => 'tinymce',),));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver
                ->setDefaults(
                        array('intention' => 'reply',));
    }

    public function getName()
    {
        return 'fos_message_reply_message_custom';
    }
}
