<?php

namespace AppBundle\Validator\Constraints;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class IsNotFullDayValidator extends ConstraintValidator
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    public function validate($date, Constraint $constraint)
    {
        $availableTickets = $this->em->getRepository('AppBundle:Ticket')->countAvailableTickets($date);
        if ($availableTickets <= 0)
        {
            $this->context
                ->buildViolation($constraint->messageMaxTicket)
                ->addViolation();
        }
    }
}
