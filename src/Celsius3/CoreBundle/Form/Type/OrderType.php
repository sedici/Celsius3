<?php

namespace Celsius3\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Celsius3\CoreBundle\Manager\MaterialTypeManager;
use Celsius3\CoreBundle\Document\Instance;
use Celsius3\CoreBundle\Document\BaseUser;
use JMS\TranslationBundle\Annotation\Ignore;

class OrderType extends AbstractType
{

    protected $instance;
    protected $material;
    protected $preferredMaterial;
    protected $user;
    protected $operator;
    protected $librarian;

    public function __construct(Instance $instance, MaterialTypeType $material = null, BaseUser $user = null, BaseUser $operator = null, $librarian = false)
    {
        $this->instance = $instance;
        $this->material = (is_null($material)) ? new JournalTypeType() : $material;

        $class = explode('\\', get_class($this->material));
        $this->preferredMaterial = lcfirst(str_replace('Type', '', end($class)));

        $this->user = $user;
        $this->operator = $operator;
        $this->librarian = $librarian;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {        
        $builder
                ->add('originalRequest', new RequestType($this->instance, $this->user, $this->operator, $this->librarian), array(
                    'label' => false,
                ))
                ->add('materialDataType', 'choice', array(
                    'choices' => array(
                        /** @Ignore */ MaterialTypeManager::TYPE__JOURNAL => ucfirst(MaterialTypeManager::TYPE__JOURNAL),
                        /** @Ignore */ MaterialTypeManager::TYPE__BOOK => ucfirst(MaterialTypeManager::TYPE__BOOK),
                        /** @Ignore */ MaterialTypeManager::TYPE__CONGRESS => ucfirst(MaterialTypeManager::TYPE__CONGRESS),
                        /** @Ignore */ MaterialTypeManager::TYPE__THESIS => ucfirst(MaterialTypeManager::TYPE__THESIS),
                        /** @Ignore */ MaterialTypeManager::TYPE__PATENT => ucfirst(MaterialTypeManager::TYPE__PATENT),
                    ),
                    'mapped' => false,
                    'data' => $this->preferredMaterial,
                    'label' => 'Material Type',
                ))
                ->add('materialData', $this->material)
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Celsius3\\CoreBundle\\Document\\Order',
            'cascade_validation' => true,
        ));
    }

    public function getName()
    {
        return 'celsius3_corebundle_ordertype';
    }

}
