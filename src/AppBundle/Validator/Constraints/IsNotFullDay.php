<?php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class IsNotFullDay extends Constraint
{
    public $message = 'All Tickets have been sold for this day. Please choose another day.';

    public function validatedBy()
    {
        return IsNotFullDayValidator::class;
    }

    public function getTargets()
    {
        return Constraint::CLASS_CONSTRAINT;
    }
}
