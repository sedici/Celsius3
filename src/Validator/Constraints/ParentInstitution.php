<?php

namespace Celsius3\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Attribute;


#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
class ParentInstitution extends Constraint
{

    public $message = 'constraint.message.parent_institution';

    public function validatedBy()
    {
        return static::class . 'Validator';
    }

}
