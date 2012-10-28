<?php

namespace Celsius\Celsius3Bundle\Filter;

class OrderFilter implements DocumentFilterInterface
{

    private $dm;
    private $specialFields = array(
        'state' => '',
    );

    private function filterByStateType($data, $query)
    {
        $stateType = $this->container->get('doctrine.odm.mongodb.document_manager')
                ->getRepository('CelsiusCelsius3Bundle:StateType')
                ->createQueryBuilder()
                ->field('name')->equals($data)
                ->getQuery()
                ->getSingleResult();

        $states = array_keys($this->container->get('doctrine.odm.mongodb.document_manager')
                        ->getRepository('CelsiusCelsius3Bundle:State')
                        ->createQueryBuilder()
                        ->field('type.id')->equals($stateType->getId())
                        ->getQuery()
                        ->execute()
                        ->toArray());

        return $query->field('currentState.id')->in($states);
    }
    
    public function __construct()
    {
        
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