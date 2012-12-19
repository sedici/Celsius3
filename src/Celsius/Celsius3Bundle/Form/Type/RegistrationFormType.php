<?php

namespace Celsius\Celsius3Bundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;
use Celsius\Celsius3Bundle\Form\EventListener\AddCustomFieldsSubscriber;
use Celsius\Celsius3Bundle\Form\EventListener\AddInstitutionFieldsSubscriber;

class RegistrationFormType extends BaseType
{

    protected $instance;
    protected $dm;

    /**
     * @param string $class The User class name
     */
    public function __construct($container, $class)
    {
        parent::__construct($class);

        $this->dm = $container->get('doctrine.odm.mongodb.document_manager');
        $url = $container->get('request')->get('url');

        $this->instance = $this->dm->getRepository('CelsiusCelsius3Bundle:Instance')
                ->findOneBy(array('url' => $url));
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        // add your custom field
        $builder->add('name', null, array('label' => 'Name'))
                ->add('surname')
                ->add('birthdate', 'birthday', array(
                    'widget' => 'single_text',
                    'format' => 'dd-MM-yyyy',
                    'attr' => array('class' => 'date')
                ))
                ->add('address')
                ->add('instance', 'instance_selector', array(
                    'data' => $this->instance,
                    'attr' => array(
                        'value' => $this->instance->getId(),
                        'readonly' => 'readonly',
                    ),
                ))
        ;
        $subscriber = new AddCustomFieldsSubscriber($builder->getFormFactory(), $this->dm, $this->instance, true);
        $builder->addEventSubscriber($subscriber);
        $subscriber2 = new AddInstitutionFieldsSubscriber($builder->getFormFactory(), $this->dm);
        $builder->addEventSubscriber($subscriber2);
    }

    public function getName()
    {
        return 'celsius_celsius3bundle_registration';
    }

}
