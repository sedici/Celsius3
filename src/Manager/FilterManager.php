<?php

/*
 * Celsius3 - Order management
 * Copyright (C) 2014 PREBI-SEDICI <info@prebi.unlp.edu.ar> http://prebi.unlp.edu.ar http://sedici.unlp.edu.ar
 *
 * This file is part of Celsius3.
 *
 * Celsius3 is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Celsius3 is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Celsius3.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Celsius3\Manager;

use Celsius3\Entity\BaseUser;
use Celsius3\Entity\Catalog;
use Celsius3\Entity\Instance;
use Celsius3\Entity\Order;
use Celsius3\Filter\BaseUserFilter;
use Celsius3\Filter\CatalogFilter;
use Celsius3\Filter\OrderFilter;
use Celsius3\Guesser\FieldGuesser;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Form\FormInterface;
use Celsius3\Exception\Exception;
use Exception as GlobalException;
use ReflectionClass;

class FilterManager
{
    private $field_guesser;
    /**
     * @var OrderFilter
     */
    private $orderFilter;
    /**
     * @var BaseUserFilter
     */
    private $baseUserFilter;
    /**
     * @var CatalogFilter
     */
    private $catalogFilter;

    public function __construct(
        FieldGuesser $field_guesser,
        OrderFilter $orderFilter,
        BaseUserFilter $baseUserFilter,
        CatalogFilter $catalogFilter
    ) {
        $this->field_guesser = $field_guesser;
        $this->orderFilter = $orderFilter;
        $this->baseUserFilter = $baseUserFilter;
        $this->catalogFilter = $catalogFilter;
    }

    // public function filter(QueryBuilder $query, FormInterface $form, string $class, Instance $instance = null)
    // {
    //     $customFilter = $this->getCustomFilterClass($class);

    //     if ($form->getData()) {
    //         foreach ($form->getData() as $key => $data) {
    //             if (!is_null($data) && $data !== '' && count($data) > 0) {
    //                 if (!is_null($customFilter) && $customFilter->hasCustomFilter($key)) {
    //                     $query = $customFilter->applyCustomFilter($key, $data, $query, $instance);
    //                 } else {
    //                     $query = $this->applyStandardFilter($class, $key, $data, $query);
    //                 }
    //             }
    //         }
    //     }

    //     return $query;
    // }


    public function filter(QueryBuilder $query, FormInterface $form, string $class, Instance $instance = null): QueryBuilder
    {


        // REVSAR CAMPOS RELACIÓN
        // REVSAR CAMPOS RELACIÓN
        // REVSAR CAMPOS RELACIÓN
        // REVSAR CAMPOS RELACIÓN
        // REVSAR CAMPOS RELACIÓN
        

        $customFilter = $this->getCustomFilterClass($class);

        // throw new GlobalException((string) var_dump($form->all()));

        if ($form->isSubmitted() && $form->isValid()) {
            $entityData = $form->getData();
            $reflectionClass = new ReflectionClass($entityData);
            $properties = $reflectionClass->getProperties();

            foreach ($form->all() as $property) {
                // $property->setAccessible(true);
                $key = $property->getName();
                $value = $property->getData();

                // throw new GlobalException((string) $key . ' = ' . $filterValues[$key] . ', ');

                if ($value !== null && $value !== '' && (is_array($value) ? count($value) > 0 : true)) {
                    // throw new GlobalException((string) $key . ' = ' . $value . ', ');
                    $query = ($customFilter !== null && $customFilter->hasCustomFilter($key))
                        ? $customFilter->applyCustomFilter($key, $value, $query, $instance)
                        : $this->applyStandardFilter($class, $key, $value, $query);
                }
            }
        }

        // throw new GlobalException((string) var_dump($form));

        return $query;
    }

    private function getCustomFilterClass($class)
    {
        $filter = null;
        switch ($class) {
            case Order::class:
                $filter = $this->orderFilter;
                break;
            case BaseUser::class:
                $filter = $this->baseUserFilter;
                break;
            case Catalog::class:
                $filter = $this->catalogFilter;
                break;
        }

        return $filter;
    }

    private function applyStandardFilter($class, $key, $data, QueryBuilder $query)
    {
        $alias = $query->getRootAliases()[0];
        switch ($this->field_guesser->getDbType($class, $key)) {
            case 'string':
                $query = $query->andWhere($alias . '.' . $key . " LIKE :$key")
                    ->setParameter("$key", '%' . $data . '%');
                break;
            case 'boolean':
                if ("" !== $data) {
                    $query = $query->andWhere($alias . '.' . $key . " = :$key")
                        ->setParameter("$key", (boolean)$data);
                }
                break;
            case 'integer':
                $query = $query->andWhere($alias . '.' . $key . " = :$key")
                    ->setParameter("$key", (int)$data);
                break;
            case 'entity':
            case 'collection':
                $query = $query->andWhere($alias . '.' . $key . " = :$key")
                    ->setParameter("$key", $data);
                break;
            default:
                $query = $query->andWhere($alias . '.' . $key . " = :$key")
                    ->setParameter("$key", $data);
                break;
        }

        return $query;
    }
}
