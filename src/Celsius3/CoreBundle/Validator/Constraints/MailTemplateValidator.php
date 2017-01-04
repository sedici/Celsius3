<?php

namespace Celsius3\CoreBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class MailTemplateValidator extends ConstraintValidator
{
    private $variables = [
        'user.full_name',
        'user.surname',
        'user.name',
        'user.username',
        'instance.name',
        'instance.abbreviation',
        'instance.website',
        'instance.email',
        'order.code',
        'order.material_data.title',
        'url',
    ];

    public function validate($value, Constraint $constraint)
    {
        preg_match_all('/\{\{([[:alpha:]\ \.\_])+?\}\}/', $value, $match);

        $templateVariables = [];
        foreach ($match[0] as $key => $value) {
            $templateVariables[$key] = str_replace(['{{', '}}', ' '], '', $value);
        }

        $intersection = array_intersect($templateVariables, $this->variables);

        if (count(array_diff($templateVariables, $intersection)) > 0) {
            $this->context->buildViolation($constraint->message)->addViolation();
        }
    }
}
