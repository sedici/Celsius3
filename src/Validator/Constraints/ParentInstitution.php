<?php

namespace Celsius3\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ParentInstitution extends Constraint
{

    public $message = 'constraint.message.parent_institution';

    public function validatedBy()
    {
        return get_class($this) . 'Validator';
    }

}
