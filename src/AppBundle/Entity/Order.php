<?php

namespace AppBundle\Entity;

use AppBundle\Services\PriceCalculator\PriceCalculator;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use AppBundle\Validator\Constraints as LouvreAssert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * Order
 *
 * @ORM\Table(name="visit_order")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\OrderRepository")
 * @LouvreAssert\IsNotFullDay()
 */
class Order
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     *
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;


    /**
     * @var Ticket[]
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Ticket", mappedBy="order", cascade={"persist"})
     * @Assert\Valid()
     */
    private $tickets;

    /**
     * @var \DateTime
     * @Assert\DateTime()
     * @Assert\NotBlank()
     * @ORM\Column(name="purchase_date", type="datetime")
     */
    private $purchaseDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_of_visit", type="datetime")
     * @Assert\DateTime()
     * @Assert\NotNull(message="Please enter a date")
     * @Assert\GreaterThanOrEqual("today", message="Please enter a valid date.")
     * @LouvreAssert\IsNotHolliday()
     * @LouvreAssert\IsNotClosingDay()
     */
    private $dateOfVisit;

    /**
     * @var bool
     * @ORM\Column(name="full_day_ticket", type="boolean")
     */
    private $fullDayTicket;



    /**
     * @var string
     * @Assert\Email()
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;

    /**
     * @var string
     * @ORM\Column(name="order_number", type="string", length=255)
     */
    private $orderNumber;




    /**
     * @var int
     *
     * @ORM\Column(name="total", type="smallint")
     */
    private $total;



    /**
     * Order constructor.
     */
    public function __construct(){
        $this->setTotal(0);
        $this->setOrderNumber(strtoupper(uniqid("CMD")));
        $this->setPurchaseDate(new \Datetime());
        $this->tickets = new ArrayCollection();
    }


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set orderNumber
     *
     * @param string $orderNumber
     *
     * @return Order
     */
    public function setOrderNumber($orderNumber)
    {
        $this->orderNumber = $orderNumber;

        return $this;
    }

    /**
     * Get orderNumber
     *
     * @return string
     */
    public function getOrderNumber()
    {
        return $this->orderNumber;
    }

    /**
     * Set purchaseDate
     *
     * @param \DateTime $purchaseDate
     *
     * @return Order
     */
    public function setPurchaseDate($purchaseDate)
    {
        $this->purchaseDate = $purchaseDate;

        return $this;
    }

    /**
     * Get purchaseDate
     *
     * @return \DateTime
     */
    public function getPurchaseDate()
    {
        return $this->purchaseDate;
    }

    /**
     * Set dateOfVisit
     *
     * @param \DateTime $dateOfVisit
     *
     * @return Order
     */
    public function setDateOfVisit($dateOfVisit)
    {
        $this->dateOfVisit = $dateOfVisit;

        return $this;
    }

    /**
     * Get dateOfVisit
     *
     * @return \DateTime
     */
    public function getDateOfVisit()
    {
        return $this->dateOfVisit;
    }

    /**
     * Set fullDayTicket
     *
     * @param boolean $fullDayTicket
     *
     * @return Order
     */
    public function setFullDayTicket($fullDayTicket)
    {
        $this->fullDayTicket = $fullDayTicket;

        return $this;
    }

    /**
     * Get fullDayTicket
     *
     * @return boolean
     */
    public function getFullDayTicket()
    {
        return $this->fullDayTicket;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Order
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set total
     *
     * @param integer $total
     *
     * @return Order
     */
    public function setTotal($total)
    {
        $this->total = $total;

        return $this;
    }

    /**
     * Get total
     *
     * @return integer
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * Add ticket
     *
     * @param \AppBundle\Entity\Ticket $ticket
     *
     * @return Order
     */
    public function addTicket(\AppBundle\Entity\Ticket $ticket)
    {
        $this->tickets[] = $ticket;
        $ticket->setOrder($this);

        return $this;
    }

    /**
     * Remove ticket
     *
     * @param \AppBundle\Entity\Ticket $ticket
     */
    public function removeTicket(\AppBundle\Entity\Ticket $ticket)
    {
        $this->tickets->removeElement($ticket);
    }

    /**
     * Get tickets
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTickets()
    {
        return $this->tickets;
    }


    /**
     * @Assert\Callback
     */
    public function validate(ExecutionContextInterface $context)
    {
        $priceCalculator = new PriceCalculator();
        $order = $priceCalculator->setTotalPrice($this);



        if ($order->getTotal() == 0) {
            $context->buildViolation('Children must be accompanied')
                ->atPath('Tickets')
                ->addViolation();
        }
    }
}
