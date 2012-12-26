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
            FormEvents::PRE_SET_DATA => 'preSetData',
            FormEvents::PRE_BIND => 'preBind',
        );
    }

    private function addInstitutionFields(FormEvent $event, $bind)
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

        $country = null;
        $city = null;
        $institution = null;

        if ($bind && array_key_exists('institution', $data))
        {
            $institution = $this->dm->find('CelsiusCelsius3Bundle:Institution', $data['institution']);
            $city = $institution->getCity();
            if (!is_null($city))
            {
                $country = $city->getCountry();
            }
        }

        $form->add($this->factory->createNamed('country', 'document', $country, array(
                    'class' => 'CelsiusCelsius3Bundle:Country',
                    'property_path' => false,
                    'empty_value' => '',
                    'required' => false,
                    'query_builder' => function(DocumentRepository $dr)
                    {
                        return $dr->createQueryBuilder()
                                        ->sort('name');
                    },
                )));
        $form->add($this->factory->createNamed('city', 'document', $city, array(
                    'class' => 'CelsiusCelsius3Bundle:City',
                    'property_path' => false,
                    'empty_value' => '',
                    'required' => false,
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
                )));
        $form->add($this->factory->createNamed('institution', 'document', $institution, array(
                    'class' => 'CelsiusCelsius3Bundle:Institution',
                    'empty_value' => '',
                    'expanded' => true,
                    'query_builder' => function (DocumentRepository $repository) use ($city, $country)
                    {
                        $qb = $repository->createQueryBuilder();

                        if ($city instanceof City)
                        {
                            $qb = $qb->field('city.id')->equals($city->getId());
                        } else if ($country instanceof Country)
                        {
                            $qb = $qb->field('country.id')->equals($country->getId());
                        } else
                        {
                            $qb = $qb->field('city.id')->equals(null);
                        }

                        return $qb->sort('name');
                    },
                )));
    }

    public function preSetData(FormEvent $event)
    {
        $this->addInstitutionFields($event, false);
    }

    public function preBind(FormEvent $event)
    {
        $this->addInstitutionFields($event, true);
    }

}