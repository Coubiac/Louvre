<?php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class IsNotHolliday extends Constraint
{
    public $message = 'This date is an official Holliday, The museum is closed';

    public function validatedBy()
    {
        return IsNotHollidayValidator::class;
    }
}
