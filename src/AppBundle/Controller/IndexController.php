<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Order;
use AppBundle\Form\OrderType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;


class IndexController extends Controller
{
    /**
     * @Route("/", name="index")
     * @Method({"GET", "POST"})
     */
    public function indexAction(Request $request)
    {
        $order = new Order();
        $form = $this->createForm(OrderType::class, $order);
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($order);
            $tickets = $order->getTickets();
            foreach($tickets as $ticket){
                $ticket->setOrder($order);
            }
            $em->flush();
            return $this->redirectToRoute("confirmation", array('id' => $order->getId()));
        }
        return $this->render('default/index.html.twig', array(
            'form' => $form->createView(),
        ));

    }


    /**
     * @Route("/confirmation/{id}", name="confirmation")
     * @Method({"GET"})
     */
    public function confirmationAction(Order $order)
    {

        $message = \Swift_Message::newInstance()
            ->setSubject('Louvre Museum : Order confirmation')
            ->setFrom(array('noreply.louvre@gmail.com' => 'Louvre Museum'))
            ->setTo($order->getEmail())
            ->setBody(
                $this->render('mails/confimationMail.html.twig', array('order' => $order)), 'text/html')
        ;
        dump($message);
        $this->get('mailer')->send($message);
        return $this->redirectToRoute('index');

    }

}
