<?php

namespace Celsius3\CoreBundle\Form\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Doctrine\ODM\MongoDB\DocumentRepository;
use Doctrine\ODM\MongoDB\DocumentManager;
use Celsius3\CoreBundle\Document\Country;
use Celsius3\CoreBundle\Document\City;

class AddInstitutionFieldsSubscriber implements EventSubscriberInterface
{

    private $factory;
    private $dm;
    private $property_path;
    private $required;
    private $country_mapped;
    private $city_mapped;
    private $with_filter;

    public function __construct(FormFactoryInterface $factory, DocumentManager $dm, $property_path = 'institution', $required = true, $country_mapped = false, $city_mapped = false, $with_filter = false)
    {
        $this->factory = $factory;
        $this->dm = $dm;
        $this->property_path = $property_path;
        $this->required = $required;
        $this->country_mapped = $country_mapped;
        $this->city_mapped = $city_mapped;
        $this->with_filter = $with_filter;
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
        if (null === $data) {
            return;
        }

        $country = null;
        $city = null;
        $institution = null;

        if ($bind && array_key_exists($this->property_path, $data)) {
            $institution = $this->dm->find('Celsius3CoreBundle:Institution', $data[$this->property_path]);
        } elseif (is_object($data)) {
            $function = 'get' . ucfirst($this->property_path);
            $institution = $data->$function();
        }

        if ($institution) {
            $city = $institution->getCity();
            if (is_null($city)) {
                $country = $institution->getCountry();
            } else {
                $country = $city->getCountry();
            }
        }

        if ($this->with_filter) {
            $filter = null;
            $form->add($this->factory->createNamed('filter', 'choice', $filter, array(
                        'choices' => array(
                            'liblink' => 'Liblink',
                            'celsius3' => 'Celsius3',
                        ),
                        'mapped' => false,
                        'empty_value' => 'All',
                        'required' => false,
                        'expanded' => true,
                        'attr' => array(
                            'class' => 'filter-select'
                        ),
                        'auto_initialize' => false,
            )));
        }

        $form->add($this->factory->createNamed('country', 'document', $country, array(
                    'class' => 'Celsius3CoreBundle:Country',
                    'mapped' => $this->country_mapped,
                    'empty_value' => '',
                    'required' => false,
                    'query_builder' => function (DocumentRepository $dr) {
                        return $dr
                                        ->createQueryBuilder()
                                        ->sort('name', 'asc');
                    },
                    'attr' => array(
                        'class' => 'country-select'
                    ),
                    'auto_initialize' => false,
        )));

        $form->add($this->factory->createNamed('city', 'document', $city, array(
                    'class' => 'Celsius3CoreBundle:City',
                    'mapped' => $this->city_mapped,
                    'empty_value' => '',
                    'required' => false,
                    'query_builder' => function (DocumentRepository $repository) use ($country) {
                        $qb = $repository->createQueryBuilder();

                        if ($country instanceof Country) {
                            $qb = $qb->field('country.id')->equals($country->getId());
                        } else {
                            $qb = $qb->field('country.id')->equals(null);
                        }

                        return $qb->sort('name', 'asc');
                    },
                    'attr' => array(
                        'class' => 'city-select'
                    ),
                    'auto_initialize' => false,
        )));

        $form->add($this->factory->createNamed($this->property_path, 'document', $institution, array(
                    'class' => 'Celsius3CoreBundle:Institution',
                    'property_path' => $this->property_path,
                    'label' => ucfirst($this->property_path),
                    'empty_value' => '',
                    'required' => $this->required,
                    'query_builder' => function (DocumentRepository $repository) use ($city, $country) {
                        $qb = $repository->createQueryBuilder();

                        if ($city instanceof City) {
                            $qb = $qb->field('city.id')->equals($city->getId());
                        } else if ($country instanceof Country) {
                            $qb = $qb->field('country.id')->equals($country->getId())
                                            ->field('city.id')->equals(null);
                        } else {
                            $qb = $qb->field('city.id')->equals(null)
                                            ->field('country.id')->equals(null);
                        }

                        return $qb->sort('name', 'asc');
                    },
                    'attr' => array(
                        'class' => 'institution-select'
                    ),
                    'auto_initialize' => false,
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
