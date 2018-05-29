<?php

namespace AppBundle\Services\Notification;

class Notification extends \Twig_Extension
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

    public function sendConfirmationAction($order)
    {
        $message = \Swift_Message::newInstance()
            ->setSubject('Louvre Museum : Order confirmation')
            ->setFrom(['noreply@louvre.com' => 'Louvre Museum'])
            ->setTo($order->getEmail())
            ->setBody($this->twig->render('mails/confimationMail.html.twig', ['order' => $order]), 'text/html');
        $this->mailer->send($message);
    }
}
