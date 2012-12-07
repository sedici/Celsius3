<?php

namespace Celsius\Celsius3Bundle\Form\EventListener;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Doctrine\ODM\MongoDB\DocumentManager;
use Celsius\Celsius3Bundle\Document\Instance;

class AddCustomFieldsSubscriber implements EventSubscriberInterface
{

    private $factory;
    private $dm;
    private $instance;
    private $registration;

    public function __construct(FormFactoryInterface $factory, DocumentManager $dm, Instance $instance, $registration)
    {
        $this->factory = $factory;
        $this->dm = $dm;
        $this->instance = $instance;
        $this->registration = $registration;
    }

    public static function getSubscribedEvents()
    {
        return array(
            FormEvents::POST_SET_DATA => 'postSetData',
        );
    }

    public function postSetData(FormEvent $event)
    {
        $data = $event->getData();
        $form = $event->getForm();

        // During form creation setData() is called with null as an argument
        // by the FormBuilder constructor. You're only concerned with when
        // setData is called with an actual Entity object in it (whether new
        // or fetched with Doctrine). This if statement lets you skip right
        // over the null condition.
        if (null === $data)
        {
            return;
        }

        $userId = $data->getId() ? $data->getId() : null;

        $query = $this->dm->getRepository('CelsiusCelsius3Bundle:CustomUserField')
                        ->createQueryBuilder()
                        ->field('instance.id')->equals($this->instance->getId());

        if ($this->registration)
        {
            $query = $query->field('private')->equals(false);
        }

        $fields = $query->getQuery()
                ->execute();

        foreach ($fields as $field)
        {
            if ($userId)
            {
                $value = $this->dm->getRepository('CelsiusCelsius3Bundle:CustomUserValue')
                        ->createQueryBuilder()
                        ->field('field.id')->equals($field->getId())
                        ->field('user.id')->equals($userId)
                        ->getQuery()
                        ->getSingleResult();
            } else
            {
                $value = null;
            }

            $form->add($this->factory->createNamed($field->getKey(), 'text', $value ? $value->getValue() : null, array(
                        'label' => ucfirst($field->getName()),
                        'required' => $field->getRequired(),
                        'property_path' => false,
                    )));
        }
    }

}