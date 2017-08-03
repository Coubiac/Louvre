<?php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class IsNotClosingDayValidator extends ConstraintValidator
{
    public function validate($date, Constraint $constraint)
    {
        $date = $date->format('m/d/Y');

        $date = strtotime($date);
        if (date('w', $date) === 'Sun' || date('w', $date) === 'Tue') {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}
