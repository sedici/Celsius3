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

namespace Celsius3\CoreBundle\Manager;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Form\FormInterface;
use Celsius3\CoreBundle\Guesser\FieldGuesser;
use Celsius3\CoreBundle\Entity\Instance;

class FilterManager
{

    public const PATH__FILTERS = 'Celsius3\\CoreBundle\\Filter\\';

    private $em;
    private $field_guesser;

    public function __construct(EntityManager $em, FieldGuesser $field_guesser)
    {
        $this->em = $em;
        $this->field_guesser = $field_guesser;
    }

    private function getCustomFilterClass($class)
    {
        $className = explode('\\', $class);
        $filterClass = self::PATH__FILTERS . end($className) . 'Filter';

        $filter = null;
        if (class_exists($filterClass)) {
            $filter = new $filterClass($this->em);
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
                            ->setParameter("$key", (boolean) $data);
                }
                break;
            case 'integer':
                $query = $query->andWhere($alias . '.' . $key . " = :$key")
                        ->setParameter("$key", (int) $data);
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

}
