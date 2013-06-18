<?php

namespace Celsius3\CoreBundle\Form\Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Celsius3\CoreBundle\Form\DataTransformer\UserToIdTransformer;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserSelectorType extends AbstractType
{

    /**
     * @var DocumentManager
     */
    private $dm;

    /**
     * @param DocumentManager $dm
     */
    public function __construct(DocumentManager $dm)
    {
        $this->dm = $dm;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $transformer = new UserToIdTransformer($this->dm);
        $builder->addModelTransformer($transformer);
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver
                ->setDefaults(
                        array(
                                'invalid_message' => 'The selected User does not exist',));
    }

    public function getParent()
    {
        return 'hidden';
    }

    public function getName()
    {
        return 'celsius3_core.user_selector';
    }

}
