<?php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class IsNotClosingDayValidator extends ConstraintValidator
{
    public function validate($date, Constraint $constraint)
    {
        //On verifie que la date choisie n'est pas un Dimanche ou un Mardi
        $date = $date->format('m/d/Y');

        $date = strtotime($date);
        if (date('D', $date) === 'Sun' || date('D', $date) === 'Tue') {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}
