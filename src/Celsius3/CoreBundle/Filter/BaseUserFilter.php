<?php

namespace Celsius3\CoreBundle\Filter;
use Doctrine\ODM\MongoDB\DocumentManager;

class BaseUserFilter implements DocumentFilterInterface
{

    private $dm;
    private $specialFields = array('state' => 'addFindByStateType',);

    public function __construct(DocumentManager $dm)
    {
        $this->dm = $dm;
    }

    public function applyCustomFilter($field_name, $data, $query, $instance)
    {
        $function = $this->specialFields[$field_name];
        return $this->dm->getRepository('Celsius3CoreBundle:BaseUser')
                ->$function($data, $query, $instance);
    }

    public function hasCustomFilter($field_name)
    {
        return array_key_exists($field_name, $this->specialFields);
    }

}
