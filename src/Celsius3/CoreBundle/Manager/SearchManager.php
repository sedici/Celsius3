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

use Celsius3\CoreBundle\Entity\Instance;
use Symfony\Component\DependencyInjection\Container;
use Elastica\Query;
use Elastica\Aggregation\Terms;
use Elastica\Aggregation\Nested;
use Elastica\Query\QueryString;
use Elastica\Query\Filtered;
use Elastica\Filter\BoolFilter;

class SearchManager
{
    private $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    private function prepareKeyword($keyword)
    {
        $search = '';
        foreach (explode(' ', trim($keyword)) as $word) {
            $search .= " *$word* ";
        }

        return $search;
    }

    private function addAgregations(Query $query)
    {
        $typesAgg = (new Terms('types'))->setField('type');
        $ownersAgg = (new Nested('owners', 'owner'))
                ->addAggregation((new Terms('owners'))->setField('owner.username'));
        $operatorsAgg = (new Nested('operators', 'operator'))
                ->addAggregation((new Terms('operators'))->setField('operator.username'));
        $materialsAgg = (new Nested('materials', 'order.materialData'))
                ->addAggregation((new Terms('materials'))->setField('order.materialData.materialType'));
        $statesAgg = (new Nested('states', 'currentState'))
                ->addAggregation((new Terms('states'))->setField('currentState.type'));

        $query->addAggregation($typesAgg);
        $query->addAggregation($ownersAgg);
        $query->addAggregation($operatorsAgg);
        $query->addAggregation($materialsAgg);
        $query->addAggregation($statesAgg);
    }

    private function addInstanceFilter(BoolFilter $boolFilter, Instance $instance)
    {
        $nestedFilter = new \Elastica\Filter\Nested();
        $nestedFilter->setPath('instance');
        $nestedFilter->setName('instance');
        $nestedFilter->setFilter((new \Elastica\Filter\Term())->setTerm('instance.id', $instance->getId()));
        $boolFilter->addMust($nestedFilter);
    }

    private function addOperatorsFilter(BoolFilter $boolFilter, $value)
    {
        $nestedFilter = new \Elastica\Filter\Nested();
        $nestedFilter->setPath('operator');
        $nestedFilter->setName('operator');
        $nestedFilter->setFilter((new \Elastica\Filter\Term())->setTerm('operator.username', $value));
        $boolFilter->addMust($nestedFilter);
    }

    private function addOwnersFilter(BoolFilter $boolFilter, $value)
    {
        $nestedFilter = new \Elastica\Filter\Nested();
        $nestedFilter->setPath('owner');
        $nestedFilter->setName('owner');
        $nestedFilter->setFilter((new \Elastica\Filter\Term())->setTerm('owner.username', $value));
        $boolFilter->addMust($nestedFilter);
    }

    private function addMaterialsFilter(BoolFilter $boolFilter, $value)
    {
        $nestedFilter = new \Elastica\Filter\Nested();
        $nestedFilter->setPath('order.materialData');
        $nestedFilter->setName('materials');
        $nestedFilter->setFilter((new \Elastica\Filter\Term())->setTerm('order.materialData.materialType', $value));
        $boolFilter->addMust($nestedFilter);
    }

    private function addStatesFilter(BoolFilter $boolFilter, $value)
    {
        $nestedFilter = new \Elastica\Filter\Nested();
        $nestedFilter->setPath('currentState');
        $nestedFilter->setName('states');
        $nestedFilter->setFilter((new \Elastica\Filter\Term())->setTerm('currentState.type', $value));
        $boolFilter->addMust($nestedFilter);
    }

    private function addTypesFilter(BoolFilter $boolFilter, $value)
    {
        $boolFilter->addMust((new \Elastica\Filter\Term())->setTerm('type', $value));
    }

    private function addAggregationsFilters(BoolFilter $boolFilter, array $filters = array())
    {
        if (array_key_exists('instance', $filters)) {
            $this->addInstanceFilter($boolFilter, $filters['instance']);
        }
        if (array_key_exists('operators', $filters)) {
            $this->addOperatorsFilter($boolFilter, $filters['operators']);
        }
        if (array_key_exists('owners', $filters)) {
            $this->addOwnersFilter($boolFilter, $filters['owners']);
        }
        if (array_key_exists('materials', $filters)) {
            $this->addMaterialsFilter($boolFilter, $filters['materials']);
        }
        if (array_key_exists('states', $filters)) {
            $this->addStatesFilter($boolFilter, $filters['states']);
        }
        if (array_key_exists('types', $filters)) {
            $this->addTypesFilter($boolFilter, $filters['types']);
        }
    }

    public function search($keyword, $filters, Instance $instance)
    {
        $query = new Query();
        $this->addAgregations($query);

        $queryString = new QueryString($this->prepareKeyword($keyword));
        $boolFilter = new \Elastica\Filter\BoolFilter();

        $this->addInstanceFilter($boolFilter, $instance);
        $this->addAggregationsFilters($boolFilter, $filters);

        $filtered = new Filtered($queryString, $boolFilter);

        $query->setQuery($filtered);

        $finder = $this->container->get('fos_elastica.finder.app.request');

        return $finder->createPaginatorAdapter($query);
    }

    public function getAggsUsersData($aggs)
    {
        $usernames = array();
        foreach ($aggs['owners']['owners']['buckets'] as $user) {
            $usernames[] = $user['key'];
        }

        foreach ($aggs['operators']['operators']['buckets'] as $user) {
            $usernames[] = $user['key'];
        }

        $usernames = array_unique($usernames);

        $baseusers = $this->container->get('doctrine.orm.entity_manager')
                ->getRepository('Celsius3CoreBundle:BaseUser')
                ->findBy(['username' => $usernames]);

        $users = [];
        foreach ($baseusers as $user) {
            $users[strtolower($user->getUsername())] = [
                'name' => $user->getName(),
                'surname' => $user->getSurname(),
            ];
        }

        return $users;
    }

    public function validate(&$keyword)
    {
        $keyword = trim($keyword);

        if (preg_match('/^([[:alnum:][:blank:]äáàëéèíìöóòúùñçÁÉÍÓÚÀÈÌÒÙÄËÖÑÇ])*$/', $keyword)) {
            return true;
        }

        return false;
    }
}
