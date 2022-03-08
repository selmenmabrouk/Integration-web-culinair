<?php

namespace App\Controller\front_end;

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
     * @Route("/", name="payment")
     */
    public function index(): Response
    {
        return $this->render('front_end/payment/index.html.twig', [
            'controller_name' => 'PaymentController',
        ]);
    }

    /**
     * @Route("/checkout", name="stripe_checkout")
     * @throws ApiErrorException
     */
    public function checkout($stripeSK): RedirectResponse
    {
        Stripe::setApiKey($stripeSK);
        $session = Session::create([
            'line_items' => [[
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => 'T-shirt',
                    ],
                    'unit_amount' => 2000,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => $this->generateUrl('success_url'), [], UrlGeneratorInterface::ABSOLUTE_URL,
            'cancel_url' => $this->generateUrl('cancel_url'), [], UrlGeneratorInterface::ABSOLUTE_URL,
        ]);
        //  return $response->withHeader('Location', $session->url)->withStatus(303);
        return $this->redirect($session->url, 303);
    }

    /**
     * @Route("/success-url", name="success_url")
     */
    public function successUrl(): Response
    {
        return $this->render('front_end/payment/success.html.twig', []);
    }

    /**
     * @Route("/cancel-url", name="cancel_url")
     */
    public function cancelUrl(): Response
    {
        return $this->render('front_end/payment/cancel.html.twig', []);
    }


    /* private $Reservation;

    private $config;

    private $secretKey;

    private $session;

    public function __construct(EntityManagerInterface $objectManager)
    {
        // TODO: Load the config in a cleaner way
        $this->config = require(__DIR__ . '/../../../config/stripe.php');
        $this->secretKey = $this->config['secret_key'];
        $this->session = new Session();
    }


    /**
     * @Route("/checkout/stripe-checkout", name="checkout")
     */
    /* public function stripeCheckout(Request $req, Reservation $Reservation, Evenement $Prix)
    {
        if (!$this->session->get('checkout/payment')) {
            return $this->redirectToRoute('all_Reservation');
        }
        

        $stripe = new \Stripe\Stripe::setApiKey($this->secretKey);

        $token = $req->get('stripeToken');

        // Stripe expects prices in sarefff
        $Prix = $this->$Prix * 100;

        try {
            \Stripe\Charge::create([
                'amount' => $Prix,
                'currency' => 'eur',
                'source' => $token,
            ]);
        } catch (\Exception $e) {
            $this->addFlash('danger', 'Paiement refusé');
            return $this->redirectToRoute('checkout_payment');
        } catch (\Exception $e) {
            $this->addFlash('danger', 'Paiement impossible');
            return $this->redirectToRoute('checkout_payment');
        }


        // $order = $Reservation->create($this->Reservation, $user, 'stripe');

        $em = $this->getDoctrine()->getManager();
        // $em->persist($order);
        $em->flush();




        return $this->render('front_end/confirmation.html.twig');
    }*/
}
