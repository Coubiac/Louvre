<?php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class IsNotClosingDay extends Constraint
{
    public $message = 'The museum is closed on Sundays and Tuesdays. Please choose another day.';

    public function validatedBy()
    {
        return IsNotClosingDayValidator::class;
    }
}
