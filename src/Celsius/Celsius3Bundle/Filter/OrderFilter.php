<?php

namespace Celsius\Celsius3Bundle\Filter;

use Doctrine\ODM\MongoDB\DocumentManager;

class OrderFilter implements DocumentFilterInterface
{

    private $dm;
    private $specialFields = array(
        'state' => 'filterByStateType',
    );

    private function filterByStateType($data, $query)
    {
        $stateType = $this->dm->getRepository('CelsiusCelsius3Bundle:StateType')
                ->createQueryBuilder()
                ->field('name')->equals($data)
                ->getQuery()
                ->getSingleResult();

        $states = array_keys($this->dm->getRepository('CelsiusCelsius3Bundle:State')
                        ->createQueryBuilder()
                        ->field('isCurrent')->equals(true)
                        ->field('type.id')->equals($stateType->getId())
                        ->getQuery()
                        ->execute()
                        ->toArray());

        return $query->field('currentState.id')->in($states);
    }
    
    public function __construct(DocumentManager $dm)
    {
        $this->dm = $dm;
    }

    public function applyCustomFilter($field_name, $data, $query)
    {
        $function = $this->specialFields[$field_name];
        return $this->$function($data, $query);
    }

    public function hasCustomFilter($field_name)
    {
        return array_key_exists($field_name, $this->specialFields);
    }

}