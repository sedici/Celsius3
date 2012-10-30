<?php

namespace Celsius\Celsius3Bundle\Filter;

use Doctrine\ODM\MongoDB\DocumentManager;

class OrderFilter implements DocumentFilterInterface
{

    private $dm;
    private $specialFields = array(
        'state' => 'filterByStateType',
    );

    private function filterByStateType($data, $query, $instance)
    {
        $stateType = $this->dm->getRepository('CelsiusCelsius3Bundle:StateType')
                ->createQueryBuilder()
                ->field('name')->equals($data)
                ->getQuery()
                ->getSingleResult();

        $states = $this->dm->getRepository('CelsiusCelsius3Bundle:State')
                        ->createQueryBuilder()
                        ->hydrate(false)
                        ->select('id')
                        ->field('isCurrent')->equals(true)
                        ->field('type.id')->equals($stateType->getId());

        if (!is_null($instance))
        {
            $states = $states->field('instance.id')->equals($instance->getId());
        }

        return $query->field('currentState.id')->in(array_keys($states->getQuery()
                                        ->execute()
                                        ->toArray()));
    }

    public function __construct(DocumentManager $dm)
    {
        $this->dm = $dm;
    }

    public function applyCustomFilter($field_name, $data, $query, $instance)
    {
        $function = $this->specialFields[$field_name];
        return $this->$function($data, $query, $instance);
    }

    public function hasCustomFilter($field_name)
    {
        return array_key_exists($field_name, $this->specialFields);
    }

}