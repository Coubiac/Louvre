<?php

namespace Tests\AppBundle\PriceCalculatorTest;

use DateTime;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use AppBundle\Entity\Order;
use AppBundle\Entity\Ticket;
use AppBundle\Services\PriceCalculator\PriceCalculator;

class PriceCalculatorTest extends KernelTestCase
{
    private $order;
    private $ticket;
    private $priceCalculator;

    protected function setUp()
    {
        $this->order = new Order();
        // TODO gerer coeff demijournée

        $this->ticket = new Ticket();
        $this->order->addTicket($this->ticket);
        $this->priceCalculator = new PriceCalculator();

    }

    public function ticketDataProvider()
    {
        return [

            // Tarif Adulte
            ['-30 years', true, false, PriceCalculator::NORMAL_PRICE],
            ['-30 years', true, true, PriceCalculator::REDUCED_PRICE],

            ['-30 years', false, false, PriceCalculator::NORMAL_PRICE * PriceCalculator::HALFDAY_COEF],
            ['-30 years', false, true, PriceCalculator::REDUCED_PRICE * PriceCalculator::HALFDAY_COEF],

            // Tarif Enfant
            ['-10 years', true, false, PriceCalculator::CHILDREN_PRICE],
            ['-10 years', true, true, PriceCalculator::CHILDREN_PRICE],



            ['-10 years', false, false, PriceCalculator::CHILDREN_PRICE * PriceCalculator::HALFDAY_COEF],
            ['-10 years', false, true, PriceCalculator::CHILDREN_PRICE * PriceCalculator::HALFDAY_COEF],


            // Tarif Senior
            ['-80 years', true, false, PriceCalculator::SENIOR_PRICE],
            ['-80 years', false, false, PriceCalculator::SENIOR_PRICE * PriceCalculator::HALFDAY_COEF],
            ['-80 years', true, true, PriceCalculator::REDUCED_PRICE],
            ['-80 years', false, true, PriceCalculator::REDUCED_PRICE * PriceCalculator::HALFDAY_COEF],


            //Tarif Bébé
            ['-3 years', true, false, PriceCalculator::FREE_PRICE]
        ];
    }

    /**
     * @dataProvider ticketDataProvider
     */
    public function testTicketPrice($modify, $orderType, $reduced, $expected)
    {
        $this->order->setDateOfVisit(new DateTime('tomorrow'))->setFullDayTicket($orderType);

        $birthdate = new DateTime('tomorrow');
        $birthdate->modify($modify);
        $this->ticket->setBirthdate($birthdate)->setReducedPrice($reduced);

        $this->order = $this->priceCalculator->setTotalPrice($this->order);


        $this->assertEquals($expected, $this->order->getTotal());

    }

}