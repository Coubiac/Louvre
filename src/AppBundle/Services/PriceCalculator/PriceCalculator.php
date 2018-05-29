<?php
/**
 * Created by PhpStorm.
 * User: ben
 * Date: 04/08/2017
 * Time: 08:32.
 */

namespace AppBundle\Services\PriceCalculator;

use AppBundle\Entity\Order;

class PriceCalculator
{
    const FREE_PRICE = 0;
    const NORMAL_PRICE = 16;
    const CHILDREN_PRICE = 8;
    const SENIOR_PRICE = 12;
    const REDUCED_PRICE = 10;
    const HALFDAY_COEF = 0.5;
    const FULLDAY_COEF = 1;

    private $priceCoef;

    public function setTotalPrice(Order $order)
    {
        if ($order->getFullDayTicket()) {
            $this->priceCoef = self::FULLDAY_COEF;
        } else {
            $this->priceCoef = self::HALFDAY_COEF;
        }

        $total = 0;
        $tickets = $order->getTickets();
        $dateOfVisit = $order->getDateOfVisit();
        foreach ($tickets as $ticket) {
            $price = 0;
            $birthdate = $ticket->getBirthdate();

            $age = $birthdate->diff($dateOfVisit)->format('%Y'); //On calcule l'age du visiteur à la date de la visite

            if ($age < 4) {
                $price = self::FREE_PRICE * $this->priceCoef;
            }
            if ($age >= 4 && $age < 12) {
                $price = self::CHILDREN_PRICE * $this->priceCoef;
            }
            if ($age >= 12) {
                if ($ticket->getReducedPrice()) {
                    $price = self::REDUCED_PRICE * $this->priceCoef;
                } else {
                    $price = self::NORMAL_PRICE * $this->priceCoef;
                }
            }
            if ($age >= 60) {
                if ($ticket->getReducedPrice()) {
                    $price = self::REDUCED_PRICE * $this->priceCoef;
                } else {
                    $price = self::SENIOR_PRICE * $this->priceCoef;
                }
            }

            $ticket->setPrice($price);
            $total += $price;
        }

        $order->setTotal($total);

        return $order;
    }
}
