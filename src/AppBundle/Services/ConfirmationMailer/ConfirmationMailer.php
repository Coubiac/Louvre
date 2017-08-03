<?php

namespace AppBundle\Services\ConfirmationMailer;


use AppBundle\Entity\Order;




class ConfirmationMailer extends \Twig_Extension
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @var
     */
    private $twig;




    public function __construct(\Swift_Mailer $mailer, \Twig_Environment $twig)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;

    }

    public function sendOrderConfirmation(Order $order)
    {

        $message = \Swift_Message::newInstance()
            ->setSubject('Louvre Museum : Order confirmation')
            ->setFrom(array('noreply.louvre@gmail.com' => 'Louvre Museum'))
            ->setTo($order->getEmail())
            ->setBody(
                $this->twig->render('mails/confimationMail.html.twig', array('order' => $order)), 'text/html')
        ;
        $this->mailer->send($message);
    }

}
