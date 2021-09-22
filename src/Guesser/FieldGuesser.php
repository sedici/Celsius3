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

namespace Celsius3\Guesser;

use Celsius3\Exception\NotImplementedException;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class FieldGuesser
{
    use ContainerAwareTrait;

    private $doctrine;
    private $metadata;
    private static $current_class;

    public function __construct(Registry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    protected function getMetadatas($class = null)
    {
        if ($class) {
            self::$current_class = $class;
        }
        if (isset($this->metadata[self::$current_class]) || !$class) {
            return $this->metadata[self::$current_class];
        }
        if (!$this->doctrine->getManagerForClass(self::$current_class)->getConfiguration()->getMetadataDriverImpl()->isTransient($class)) {
            $this->metadata[self::$current_class] = $this->doctrine->getManagerForClass(self::$current_class)->getClassMetadata($class);
        }

        return $this->metadata[self::$current_class];
    }

    public function getAllFields($class)
    {
        return $this->getMetadatas($class)->getFieldNames();
    }

    public function getDbType($class, $fieldName)
    {
        $metadata = $this->getMetadatas($class);
        if ($metadata->hasAssociation($fieldName)) {
            if ($metadata->isSingleValuedAssociation($fieldName)) {
                return 'entity';
            } else {
                return 'collection';
            }
        }
        if ($metadata->hasField($fieldName)) {
            return $metadata->getTypeOfField($fieldName);
        }

        return 'virtual';
    }

    public function getModelType($class, $fieldName)
    {
        $metadata = $this->getMetadatas($class);
        if ($metadata->hasAssociation($fieldName)) {
            return $metadata->getAssociationTargetClass($fieldName);
        }
        if ($metadata->hasField($fieldName)) {
            return $metadata->getTypeOfField($fieldName);
        }

        return 'virtual';
    }

    public function getSortType($dbType)
    {
        $alphabeticTypes = array(
            'string',
            'text',
        );
        $numericTypes = array(
            'decimal',
            'float',
            'integer',
            'bigint',
            'smallint',
        );
        if (in_array($dbType, $alphabeticTypes)) {
            return 'alphabetic';
        }
        if (in_array($dbType, $numericTypes)) {
            return 'numeric';
        }

        return 'default';
    }

    public function getFormType($dbType, $columnName)
    {
        $formTypes = $this->container->getParameter('admingenerator.doctrine_form_types');
        if (array_key_exists($dbType, $formTypes)) {
            return $formTypes[$dbType];
        } elseif ('virtual' === $dbType) {
            throw new NotImplementedException(
            'The dbType "'.$dbType.'" is only for list implemented '
            .'(column "'.$columnName.'" in "'.self::$current_class.'")'
            );
        } else {
            throw new NotImplementedException(
            'The dbType "'.$dbType.'" is not yet implemented '
            .'(column "'.$columnName.'" in "'.self::$current_class.'")'
            );
        }
    }

    public function getFilterType($dbType, $columnName)
    {
        $filterTypes = $this->container->getParameter('admingenerator.doctrine_filter_types');
        if (array_key_exists($dbType, $filterTypes)) {
            return $filterTypes[$dbType];
        }

        return $this->getFormType($dbType, $columnName);
    }

    public function getFormOptions($formType, $dbType, $columnName)
    {
        if ('boolean' == $dbType) {
            return array('required' => false);
        }
        if ('number' == $formType) {
            $mapping = $this->getMetadatas()->getFieldMapping($columnName);
            if (isset($mapping['scale'])) {
                $precision = $mapping['scale'];
            }
            if (isset($mapping['precision'])) {
                $precision = $mapping['precision'];
            }

            return array(
                'precision' => isset($precision) ? $precision : '',
                'required' => $this->isRequired($columnName),
            );
        }
        if (preg_match('#^entity#i', $formType) || preg_match('#entity$#i', $formType)) {
            $mapping = $this->getMetadatas()->getAssociationMapping($columnName);

            return array(
                'multiple' => ($mapping['type'] === ClassMetadataInfo::MANY_TO_MANY || $mapping['type'] === ClassMetadataInfo::ONE_TO_MANY),
                'em' => 'default',
                'class' => $mapping['targetEntity'],
                'required' => $this->isRequired($columnName),
            );
        }
        if (preg_match('#^collection#i', $formType) || preg_match('#collection$#i', $formType)) {
            return array(
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
            );
        }

        return array(
            'required' => $this->isRequired($columnName),
        );
    }

    protected function isRequired($fieldName)
    {
        $hasField = $this->getMetadatas()->hasField($fieldName);
        $hasAssociation = $this->getMetadatas()->hasAssociation($fieldName);
        $isSingleValAssoc = $this->getMetadatas()->isSingleValuedAssociation($fieldName);
        if ($hasField && (!$hasAssociation || $isSingleValAssoc)) {
            return !$this->getMetadatas()->isNullable($fieldName);
        }

        return false;
    }

    public function getFilterOptions($formType, $dbType, $ColumnName)
    {
        $options = array('required' => false);
        if ('boolean' == $dbType) {
            $options['choices'] = array(
                0 => $this->container->get('translator')
                        ->trans('boolean.no', array(), 'Admingenerator'),
                1 => $this->container->get('translator')
                        ->trans('boolean.yes', array(), 'Admingenerator'),
            );
            $options['empty_value'] = $this->container->get('translator')
                    ->trans('boolean.yes_or_no', array(), 'Admingenerator');
        }
        if (preg_match('#^entity#i', $formType) || preg_match('#entity$#i', $formType)) {
            return array_merge(
                    $this->getFormOptions($formType, $dbType, $ColumnName), $options
            );
        }
        if (preg_match('#^collection#i', $formType) || preg_match('#collection$#i', $formType)) {
            return array_merge(
                    $this->getFormOptions($formType, $dbType, $ColumnName), $options
            );
        }

        return $options;
    }

    /**
     * Find the pk name.
     */
    public function getModelPrimaryKeyName($class = null)
    {
        return $this->getMetadatas($class)->getSingleIdentifierFieldName();
    }
}
