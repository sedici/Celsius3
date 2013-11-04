<?php

namespace Celsius3\CoreBundle\Manager;

use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Query\Builder;
use Symfony\Component\Form\FormInterface;
use Celsius3\CoreBundle\Guesser\FieldGuesser;
use Celsius3\CoreBundle\Document\Instance;

class FilterManager
{

    const PATH__FILTERS = 'Celsius3\\CoreBundle\\Filter\\';

    private $dm;
    private $field_guesser;

    public function __construct(DocumentManager $dm, FieldGuesser $field_guesser)
    {
        $this->dm = $dm;
        $this->field_guesser = $field_guesser;
    }

    private function getCustomFilterClass($class)
    {
        $className = explode('\\', $class);
        $filterClass = self::PATH__FILTERS . end($className) . 'Filter';

        $filter = null;
        if (class_exists($filterClass)) {
            $filter = new $filterClass($this->dm);
        }

        return $filter;
    }

    private function applyStandardFilter($class, $key, $data, Builder $query)
    {
        switch ($this->field_guesser->getDbType($class, $key)) {
            case 'string':
                $query = $query->field($key)->equals(new \MongoRegex('/.*' . $data . '.*/i'));
                break;
            case 'boolean':
                if ("" !== $data) {
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

    public function filter(Builder $query, FormInterface $form, $class, Instance $instance = null)
    {
        $customFilter = $this->getCustomFilterClass($class);

        foreach ($form->getData() as $key => $data) {
            if (!is_null($data) && count($data) > 0) {
                if (!is_null($customFilter) && $customFilter->hasCustomFilter($key)) {
                    $query = $customFilter->applyCustomFilter($key, $data, $query, $instance);
                } else {
                    $query = $this->applyStandardFilter($class, $key, $data, $query);
                }
            }
        }

        return $query;
    }

}
