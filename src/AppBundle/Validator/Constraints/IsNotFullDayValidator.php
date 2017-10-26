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
        //On appelle une méthode du repository pour  versifier le nombre de billets restants
        $availableTickets = $this->em->getRepository('AppBundle:Ticket')->countAvailableTickets($order->getDateOfVisit());

        // Si le nombre de billets restant est inferieur au nombre de tickets commandés, on ne valide pas
        if ($availableTickets < $order->getTickets()->count())
        {
            $this->context
                ->buildViolation($constraint->messageMaxTicket)
                ->atPath('dateOfVisit')
                ->addViolation();
        }
    }
}
