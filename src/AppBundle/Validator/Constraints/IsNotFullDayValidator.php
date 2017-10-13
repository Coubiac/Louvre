<?php

namespace AppBundle\Validator\Constraints;

use AppBundle\Entity\Order;
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

    /**
     * @param Order $order
     * @param Constraint $constraint
     */
    public function validate($order, Constraint $constraint)
    {
        $availableTickets = $this->em->getRepository('AppBundle:Ticket')->countAvailableTickets($order->getDateOfVisit());
        if ($availableTickets < $order->getTickets()->count())
        {
            $this->context
                ->buildViolation($constraint->messageMaxTicket)
                ->atPath('dateOfVisit')
                ->addViolation();
        }
    }
}
