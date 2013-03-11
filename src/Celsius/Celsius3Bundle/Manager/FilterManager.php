<?php

namespace Celsius\Celsius3Bundle\Manager;

class FilterManager
{

    private $dm;
    private $fieldGuesser;

    private function getCustomFilterClass($class)
    {
        $className = explode('\\', $class);
        $filterClass = 'Celsius\\Celsius3Bundle\\Filter\\' . end($className) . 'Filter';

        $filter = null;
        if (class_exists($filterClass))
        {
            $filter = new $filterClass($this->dm);
        }

        return $filter;
    }

    private function applyStandardFilter($class, $key, $data, $query)
    {
        switch ($this->fieldGuesser->getDbType($class, $key))
        {
            case 'string':
                $query = $query->field($key)->equals(new \MongoRegex('/.*' . $data . '.*/i'));
                break;
            case 'boolean':
                if ("" !== $data)
                {
                    $query = $query->field($key)->equals((boolean) $data);
                }
                break;
            case 'int':
                $query = $query->field($key)->equals((int) $data);
                break;
            case 'document':
            case 'collection':
                $query = $query->field($key . '.id')->equals(new \MongoId($data)); //$data; data.$id
                break;
            default:
                $query = $query->field($key)->equals($data);
                break;
        }

        return $query;
    }

    public function __construct($dm, $fieldGuesser)
    {
        $this->dm = $dm;
        $this->fieldGuesser = $fieldGuesser;
    }

    public function filter($query, $form, $class, $instance = null)
    {
        $customFilter = $this->getCustomFilterClass($class);

        foreach ($form->getData() as $key => $data)
        {
            if (!is_null($data) && count($data) > 0)
            {
                if (!is_null($customFilter) && $customFilter->hasCustomFilter($key))
                {
                    $query = $customFilter->applyCustomFilter($key, $data, $query, $instance);
                } else
                {
                    $query = $this->applyStandardFilter($class, $key, $data, $query);
                }
            }
        }

        return $query;
    }

}