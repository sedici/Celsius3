<?php

namespace Celsius3\CoreBundle\Form\Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Celsius3\CoreBundle\Manager\UserManager;
use Celsius3\CoreBundle\Document\Instance;

class UserTransformType extends AbstractType
{

    protected $instance;

    public function __construct(Instance $instance = null)
    {
        $this->instance = $instance;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (!is_null($this->instance)) {
            $builder
                    ->add('type', 'choice',
                            array(
                                    'choices' => array(
                                            UserManager::ROLE_USER => 'User',
                                            UserManager::ROLE_LIBRARIAN => 'Librarian',
                                            UserManager::ROLE_ADMIN => 'Admin',),
                                    'expanded' => true,));
        } else {
            $builder
                    ->add('type', 'choice',
                            array(
                                    'choices' => array(
                                            UserManager::ROLE_USER => 'User',
                                            UserManager::ROLE_LIBRARIAN => 'Librarian',
                                            UserManager::ROLE_ADMIN => 'Admin',
                                            UserManager::ROLE_SUPER_ADMIN => 'Superadmin',),
                                    'expanded' => true,));
        }
    }

    public function getName()
    {
        return 'celsius3_corebundle_transformusertype';
    }

}
