<?php

namespace Celsius3\CoreBundle\Form\Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Celsius3\CoreBundle\Manager\OrderManager;
use Celsius3\CoreBundle\Manager\MaterialTypeManager;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Celsius3\CoreBundle\Document\Instance;
use Celsius3\CoreBundle\Document\MaterialType;
use Celsius3\CoreBundle\Document\BaseUser;

class OrderType extends AbstractType
{

    protected $instance;
    protected $material;
    protected $preferredMaterial;
    protected $user;

    public function __construct(Instance $instance = null,
            MaterialType $material = null, BaseUser $user = null)
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
        $resolver
                ->setDefaults(array('validation_groups' => array('newOrder'),));
    }

    public function getName()
    {
        return 'celsius3_corebundle_ordertype';
    }

}
