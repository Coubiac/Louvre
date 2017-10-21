<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Order;
use AppBundle\Form\OrderType;

use AppBundle\Services\Notification\Notification;
use DateTime;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;


class OrderController extends Controller
{

    /**
     * @Route("/", name = "index")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @param SessionInterface $session
     * @return Response
     */
    public function indexAction(Request $request, SessionInterface $session)
    {
        $order = new Order();
        $form = $this->createForm(OrderType::class, $order);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->get('priceCalculator')->setTotalPrice($order);
            $session->set('order', $order);

            return $this->render('default/summary.html.twig', array(
                'order' => $order));
        }

        return $this->render('default/index.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/payment", name="payment")
     * @Method({"POST"})
     * @param Request $request
     * @param SessionInterface $session
     * @param Notification $notification
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function paymentAction(Request $request, SessionInterface $session, Notification $notification)
    {

        $paymentManager = $this->get('paymentManager');
        $order = $session->get('order');
        if ($paymentManager->checkoutAction($order, $request)) {
            $notification->sendConfirmationAction($order);

            $em = $this->getDoctrine()->getManager();
            $em->persist($order);
            $em->flush();

            return $this->render('default/confirmation.html.twig', array(
                'order' => $order));
        }
        return $this->redirectToRoute('index');
    }


    /**
     * @Route("/findunavailabedates", name="findunavailabledates", condition="request.isXmlHttpRequest()")
     * @Method({"GET"})
     * @param Request $request
     * @return JsonResponse|Response
     */
    public function findUnavailableDatesAction(Request $request)
    {

        $unavailableDates = $this->getDoctrine()->getRepository('AppBundle:Order')->findUnavailableDate();
        $response = array();
        foreach ($unavailableDates as $date) {
            $date = $date->getTimestamp();
            array_push($response, $date);
        }

        return new JsonResponse($response);

    }

    /**
     * @Route("/countavailabletickets/{timestamp}", name="countavailabletickets")
     * @Method({"GET"})
     * @param $timestamp
     * @return JsonResponse|Response
     */
    public function countAvailableTicketAction($timestamp)
    {
        $date = new DateTime();
        $date->setTimestamp($timestamp);

        $availableTickets = $this->getDoctrine()->getRepository('AppBundle:Ticket')->countAvailableTickets($date);

        return new JsonResponse($availableTickets);
    }


}
