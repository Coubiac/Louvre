<?php
/**
 * Created by PhpStorm.
 * User: ben
 * Date: 31/08/2017
 * Time: 19:39
 */
namespace AppBundle\Services\PaymentManager;

use AppBundle\Entity\Order;
use Symfony\Component\HttpFoundation\Request;

class PaymentManager{

    private $stripeSecretKey;

    public function __construct($stripeSecretKey)
    {
        $this->stripeSecretKey = $stripeSecretKey;

    }

    public function checkoutAction(Order $order, Request $request){

        $total = $order->getTotal();
        $token = $request->request->get('stripeToken');
        \Stripe\Stripe::setApiKey($this->stripeSecretKey);
        try {
            \Stripe\Charge::create(array(
                "amount" => $total * 100,
                "currency" => "eur",
                "source" => $token,
                "description" => $order->getOrderNumber()));

            return true;
        }

        catch (\Stripe\Error\Card $e) {
            return false;
        }
    }

}
