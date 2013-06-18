<?php

namespace Celsius3\CoreBundle\Filter;

interface DocumentFilterInterface
{
    public function hasCustomFilter($field_name);

    public function applyCustomFilter($field_name, $data, $query, $instance);
}