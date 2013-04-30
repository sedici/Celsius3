<?php
namespace Celsius\Celsius3MessageBundle\FormType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use FOS\MessageBundle\DataTransformer\RecipientsDataTransformer;

class RecipientsHiddenType extends AbstractType
{
    /**
     * @var RecipientsDataTransformer
     */
    private $recipientsTransformer;

    /**
     * @param RecipientsDataTransformer $transformer
     */
    public function __construct(RecipientsDataTransformer $transformer)
    {
        $this->recipientsTransformer = $transformer;
    }

    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addModelTransformer($this->recipientsTransformer);
    }

    /**
     * {@inheritDoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'invalid_message' => 'The selected recipient does not exist',
            'data_class' => null,
        ));
    }

    /**
     * {@inheritDoc}
     */
    public function getParent()
    {
        return 'hidden';
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'recipients_selector_hidden';
    }
}
