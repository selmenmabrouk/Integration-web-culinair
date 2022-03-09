<?php

namespace App\Controller\front_end;

use App\Entity\Reservation;
use Stripe\Checkout\Session;
use Stripe\Exception\ApiErrorException;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/payment")
 */
class PaymentController extends AbstractController
{
    /**
     * @Route("/checkout/{idReservation}", name="stripe_checkout")
     * @throws ApiErrorException
     */
    public function checkout($idReservation): RedirectResponse
    {
        Stripe::setApiKey("sk_test_51KbPYkJcDiLRLLsUptBdeVDj9thWgBMYZkqpEwH7EfkKTvjVZyBkwd0YucWIqyPnuogKpeCmxbtf2z8XaQdM7R4300qXK7UgOC");

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => 'Reservation',
                        ],
                        'unit_amount' => 2000,
                    ],
                    'quantity' => 1,
                ]
            ],
            'mode' => 'payment',
            'success_url' => $this->generateUrl('success_url', ["idReservation" => $idReservation], UrlGeneratorInterface::ABSOLUTE_URL),
            'cancel_url' => $this->generateUrl('cancel_url', [], UrlGeneratorInterface::ABSOLUTE_URL),
        ]);

        //  return $response->withHeader('Location', $session->url)->withStatus(303);
        return $this->redirect($session->url, 303);
    }

    /**
     * @Route("/success-url/{idReservation}", name="success_url")
     */
    public function successUrl($idReservation): Response
    {
        $reservation = $this->getDoctrine()->getRepository(Reservation::class)->find($idReservation);

        $reservation->setPaid(true);
        
        $this->getDoctrine()->getManager()->persist($reservation);
        $this->getDoctrine()->getManager()->flush();

        return $this->render('front_end/payment/success.html.twig', []);
    }

    /**
     * @Route("/cancel-url", name="cancel_url")
     */
    public function cancelUrl(): Response
    {
        return $this->render('front_end/payment/cancel.html.twig', []);
    }
}
