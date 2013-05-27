<?php

namespace Celsius\Celsius3Bundle\Form\Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Celsius\Celsius3Bundle\Manager\OrderManager;
use Celsius\Celsius3Bundle\Manager\MaterialTypeManager;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class OrderType extends AbstractType
{

    protected $instance;
    protected $material;
    protected $preferredMaterial;
    protected $user;

    public function __construct($instance = null, $material = null,
            $user = null)
    {
        $this->instance = $instance;
        $this->material = (is_null($material)) ? new JournalTypeType()
                : $material;

        $class = explode('\\', get_class($this->material));
        $this->preferredMaterial = lcfirst(str_replace('Type', '', end($class)));

        $this->user = $user;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('type', 'choice',
                        array(
                                'choices' => array(
                                        OrderManager::TYPE__SEARCH => ucfirst(
                                                OrderManager::TYPE__SEARCH),
                                        OrderManager::TYPE__PROVISION => ucfirst(
                                                OrderManager::TYPE__PROVISION),),))
                ->add('comments', 'textarea', array('required' => false))
                ->add('owner', 'user_selector',
                        array(
                                'attr' => array(
                                        'value' => (!is_null($this->user)) ? $this
                                                        ->user->getId() : '',
                                        'class' => 'container',
                                        'readonly' => 'readonly',),))
                ->add('materialDataType', 'choice',
                        array(
                                'choices' => array(
                                        MaterialTypeManager::TYPE__JOURNAL => ucfirst(
                                                MaterialTypeManager::TYPE__JOURNAL),
                                        MaterialTypeManager::TYPE__BOOK => ucfirst(
                                                MaterialTypeManager::TYPE__BOOK),
                                        MaterialTypeManager::TYPE__CONGRESS => ucfirst(
                                                MaterialTypeManager::TYPE__CONGRESS),
                                        MaterialTypeManager::TYPE__THESIS => ucfirst(
                                                MaterialTypeManager::TYPE__THESIS),
                                        MaterialTypeManager::TYPE__PATENT => ucfirst(
                                                MaterialTypeManager::TYPE__PATENT),),
                                'mapped' => false,
                                'data' => $this->preferredMaterial,
                                'label' => 'Material Type'))
                ->add('materialData', $this->material);
        if (is_null($this->instance)) {
            $builder->add('instance');
        } else {
            $builder
                    ->add('instance', 'instance_selector',
                            array('data' => $this->instance,
                                    'attr' => array(
                                            'value' => $this->instance->getId(),
                                            'readonly' => 'readonly',),));
        }
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
                'validation_groups' => array('newOrder'),
        ));
    }

    public function getName()
    {
        return 'celsius_celsius3bundle_ordertype';
    }

}
