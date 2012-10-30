<?php

namespace Celsius\Celsius3Bundle\Filter;

interface DocumentFilterInterface
{
    public function hasCustomFilter($field_name);
    
    public function applyCustomFilter($field_name, $data, $query, $instance);
}