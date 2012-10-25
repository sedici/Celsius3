<?php

namespace Celsius\Celsius3Bundle\Manager;

use Symfony\Component\DependencyInjection\ContainerInterface;

class FilterManager
{

    private $container;
    private $specials = array(
        'Celsius\\Celsius3Bundle\\Document\\Order' => array(
            'state' => 'filterOrderByStateType',
        ),
    );

    private function filterOrderByStateType($data, $query)
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

    private function isSpecialField($class, $key)
    {
        return array_key_exists($class, $this->specials) && array_key_exists($key, $this->specials[$class]);
    }

    private function applySpecialFilter($class, $key, $data, $query)
    {
        $name = $this->specials[$class][$key];
        return $this->$name($data, $query);
    }

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function filter($query, $form, $class)
    {
        $guesser = $this->container->get('field.guesser');

        foreach ($form->getData() as $key => $data)
        {
            if (!is_null($data) && !$this->isSpecialField($class, $key))
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
            } elseif (!is_null($data) && $this->isSpecialField($class, $key))
            {
                $query = $this->applySpecialFilter($class, $key, $data, $query);
            }
        }

        return $query;
    }

}