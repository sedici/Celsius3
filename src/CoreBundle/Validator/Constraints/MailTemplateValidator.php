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

declare(strict_types=1);

namespace Celsius3\CoreBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

use function count;

class MailTemplateValidator extends ConstraintValidator
{
    private const VARIABLES = [
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

    public function validate($value, Constraint $constraint): void
    {
        preg_match_all('/{{([[:alpha:] ._])+?}}/', $value, $match);

        $template_variables = [];
        foreach ($match[0] as $key => $val) {
            $template_variables[$key] = str_replace(['{{', '}}', ' '], '', $val);
        }

        $intersection = array_intersect($template_variables, self::VARIABLES);

        if (count(array_diff($template_variables, $intersection)) > 0) {
            $this->context->buildViolation($constraint->message)->addViolation();
        }
    }
}
