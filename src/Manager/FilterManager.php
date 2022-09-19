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

    public function filter(QueryBuilder $query, FormInterface $form, $class, Instance $instance = null)
    {
        $customFilter = $this->getCustomFilterClass($class);

        if ($form->getData()) {
            foreach ($form->getData() as $key => $data) {
                if (!is_null($data) && $data !== '' && count($data) > 0) {
                    if (!is_null($customFilter) && $customFilter->hasCustomFilter($key)) {
                        $query = $customFilter->applyCustomFilter($key, $data, $query, $instance);
                    } else {
                        $query = $this->applyStandardFilter($class, $key, $data, $query);
                    }
                }
            }
        }

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
