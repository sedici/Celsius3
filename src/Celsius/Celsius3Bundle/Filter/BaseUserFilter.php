<?php

namespace Celsius\Celsius3Bundle\Filter;

use Doctrine\ODM\MongoDB\DocumentManager;

class BaseUserFilter implements DocumentFilterInterface
{

    private $dm;
    private $specialFields = array(
        'state' => 'filterByStateType',
    );

    private function filterByStateType($data, $query, $instance)
    {
        foreach ($data as $value)
        {
            switch ($value)
            {
                case 'enabled': $query = $query->addOr($query->expr()->field('enabled')->equals(true)
                                    ->field('locked')->equals(false));
                    break;
                case 'pending': $query = $query->addOr($query->expr()->field('enabled')->equals(false)
                                    ->field('locked')->equals(false));
                    break;
                case 'rejected': $query = $query->addOr($query->expr()->field('locked')->equals(true));
                    break;
            }
        }

        return $query;
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
