<?php

namespace Celsius3\CoreBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class MailTemplate extends Constraint {

    public $message = 'constraint.message.mail_template';

    public function validatedBy() {
        return get_class($this) . 'Validator';
    }

}
