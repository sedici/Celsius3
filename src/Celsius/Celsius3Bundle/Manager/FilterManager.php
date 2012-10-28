<?php

namespace Celsius\Celsius3Bundle\Manager;

use Symfony\Component\DependencyInjection\ContainerInterface;

class FilterManager
{

    private $container;

    private function getCustomFilterClass($class)
    {
        $className = explode('\\', get_class($class));
        $filterClass = 'Celsius\\Celsius3Bundle\\Filter\\' . $className . 'Filter';

        $filter = null;
        if (class_exists($filterClass))
        {
            $filter = new $filterClass;
        }

        return $filter;
    }

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function filter($query, $form, $class)
    {
        $guesser = $this->container->get('field.guesser');

        $customFilter = $this->getCustomFilterClass($class);

        foreach ($form->getData() as $key => $data)
        {
            if (!is_null($data))
            {
                if ($customFilter->hasCustomFilter($key))
                {
                    
                } else
                {
                    switch ($guesser->getDbType($class, $key))
                    {
                        case 'string':
                            $query = $query->field($key)->equals(new \MongoRegex('/.*' . $data . '.*/i'));
                            break;
                        case 'boolean':
                            if ("" !== $value)
                            {
                                $query = $query->field($key)->equals((boolean) $data);
                            }
                            break;
                        case 'int':
                            $query = $query->field($key)->equals((int) $data);
                            break;
                        case 'document':
                        case 'collection':
                            $query = $query->field($key . '.id')->equals(new \MongoId($data->getId()));
                            break;
                        default:
                            $query = $query->field($key)->equals($data);
                            break;
                    }
                }
            }
        }

        return $query;
    }

}