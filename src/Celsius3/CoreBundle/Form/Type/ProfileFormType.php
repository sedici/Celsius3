<?php

namespace Celsius3\CoreBundle\Form\Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProfileFormType extends AbstractType
{

    private $class;

    /**
     * @param string $class The User class name
     */
    public function __construct($class)
    {
        $this->class = $class;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', null, array('label' => 'Name'))->add('surname')
                ->add('birthdate', 'birthday',
                        array('widget' => 'single_text',
                                'format' => 'dd-MM-yyyy',
                                'attr' => array('class' => 'date')))
                ->add('address');
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver
                ->setDefaults(
                        array('data_class' => $this->class,
                                'intention' => 'profile',));
    }

    public function getName()
    {
        return 'celsius3_corebundle_profile';
    }

}
