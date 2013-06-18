<?php

namespace Celsius3\CoreBundle\Form\Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ODM\MongoDB\DocumentManager;
use Celsius3\CoreBundle\Document\Instance;
use Celsius3\CoreBundle\Form\EventListener\AddCustomFieldsSubscriber;

class BaseUserType extends AbstractType
{

    protected $instance;
    protected $dm;

    public function __construct(DocumentManager $dm, Instance $instance = null)
    {
        $this->instance = $instance;
        $this->dm = $dm;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name')->add('surname')
                ->add('birthdate', 'birthday',
                        array('widget' => 'single_text',
                                'format' => 'dd-MM-yyyy',
                                'attr' => array('class' => 'date')))
                ->add('username')->add('email', 'email')->add('address');
        if (is_null($this->instance)) {
            $builder->add('instance');
        } else {
            $builder
                    ->add('instance', 'instance_selector',
                            array('data' => $this->instance,
                                    'attr' => array(
                                            'value' => $this->instance->getId(),
                                            'readonly' => 'readonly',),));
            $subscriber = new AddCustomFieldsSubscriber(
                    $builder->getFormFactory(), $this->dm, $this->instance,
                    false);
            $builder->addEventSubscriber($subscriber);
        }
    }

    public function getName()
    {
        return 'celsius3_corebundle_baseusertype';
    }

}
