<?php

/*
 * Celsius3 - Order management
 * Copyright (C) 2014 PrEBi <info@prebi.unlp.edu.ar>
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

 namespace Celsius3\CoreBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Sabberworm\CSS\Parser;
use Sabberworm\CSS\Settings;

class ContainsCSSValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        try {
            if (!is_null($value)) {
                $parser = new Parser($value, Settings::create()->beStrict());
                $parser->parse();
            }
        } catch (\Exception $e) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}
