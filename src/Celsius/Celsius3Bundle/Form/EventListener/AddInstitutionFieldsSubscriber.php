<?php

namespace Celsius\Celsius3Bundle\Form\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Doctrine\ODM\MongoDB\DocumentRepository;
use Doctrine\ODM\MongoDB\DocumentManager;
use Celsius\Celsius3Bundle\Document\Country;
use Celsius\Celsius3Bundle\Document\City;

class AddInstitutionFieldsSubscriber implements EventSubscriberInterface
{

    private $factory;
    private $dm;

    public function __construct(FormFactoryInterface $factory, DocumentManager $dm)
    {
        $this->factory = $factory;
        $this->dm = $dm;
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
        $extraData = $event->getForm()->getExtraData();
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

        $country = array_key_exists('country', $extraData) ? $extraData['country'] : null;
        $city = array_key_exists('city', $extraData) ? $extraData['city'] : null;
        $institution = $data->getInstitution();

        $form->add($this->factory->createNamed('country', 'document', null, array(
                    'class' => 'CelsiusCelsius3Bundle:Country',
                    'property_path' => false,
                    'empty_value' => '',
                    'query_builder' => function(DocumentRepository $dr)
                    {
                        return $dr->createQueryBuilder()
                                        ->sort('name');
                    },
                    'data' => $country,
                )));
        $form->add($this->factory->createNamed('city', 'document', null, array(
                    'class' => 'CelsiusCelsius3Bundle:City',
                    'property_path' => false,
                    'empty_value' => '',
                    'query_builder' => function (DocumentRepository $repository) use ($country)
                    {
                        $qb = $repository->createQueryBuilder();

                        if ($country instanceof Country)
                        {
                            $qb = $qb->field('country.id')->equals($country->getId());
                        } else
                        {
                            $qb = $qb->field('country.id')->equals(null);
                        }

                        return $qb;
                    },
                    'data' => $city,
                )));
        $form->add($this->factory->createNamed('institution', 'document', null, array(
                    'class' => 'CelsiusCelsius3Bundle:Institution',
                    'empty_value' => '',
                    'expanded' => true,
                    'query_builder' => function (DocumentRepository $repository) use ($city)
                    {
                        $qb = $repository->createQueryBuilder();

                        if ($city instanceof City)
                        {
                            $qb = $qb->field('city.id')->equals($city->getId());
                        } else
                        {
                            $qb = $qb->field('city.id')->equals(null);
                        }

                        return $qb->sort('name');
                    },
                    'data' => $institution,
                )));
    }

}