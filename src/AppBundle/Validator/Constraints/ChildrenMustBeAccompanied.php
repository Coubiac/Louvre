<?php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ChildrenMustBeAccompanied extends Constraint
{
    public $message = 'Children must be Accompanied !';

    public function validatedBy()
    {
        return ChildrenMustBeAccompaniedValidator::class;
    }
}