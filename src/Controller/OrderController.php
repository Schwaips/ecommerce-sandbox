<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Order;
use App\Entity\OrderDetail;
use App\Form\OrderType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class OrderController extends AbstractController
{

    /**
     * 1er étape de la commande - Choix de l'adresse & du transporteur
     */
    #[Route('/commande/livraison', name: 'app_order')]
    public function index(Request $request): Response
    {
      $addresses = $this->getUser()->getAddresses();

      if(count($addresses) == 0) {
        $this->addFlash('warning', 'Vous devez ajouter une adresse de livraison avant de passer une commande.');
        return $this->redirectToRoute('app_account_addresses_form'); // Redirige vers la page d'ajout d'adresse
      }

       $form = $this->createForm(OrderType::class, null, [
         'addresses' => $addresses,
         'action' => $this->generateUrl('app_order_summary'),
       ]);

        return $this->render('order/index.html.twig', [
          'deliveryForm' => $form->createView(),
        ]);
    }

    /**
     * 2eme étape de la commande - récapitulatif
     * Insertion en base de données 
     * Préparation du paiement vers stripe.
     */
    #[Route('/commande/recapitulatif', name: 'app_order_summary')]
    public function add(Request $request, Cart $cart, EntityManagerInterface $entityManager): Response
    {
      if($request->getMethod() != 'POST') {
        return $this->redirectToRoute('app_order');
      }
    
      $products = $cart->getCart();
      if(count($products) == 0) {
        $this->addFlash('warning', 'Votre panier est vide. Vous ne pouvez pas passer de commande.');
        return $this->redirectToRoute('app_cart');
      }
    
      $form = $this->createForm(OrderType::class, null, [
        'addresses' => $this->getUser()->getAddresses()
      ]);

      $form->handleRequest($request);
      if ($form->isSubmitted() && $form->isValid()) {
          // Récupération des données du formulaire
          $data = $form->getData();
          $deliveryAddress = $data['addresses'];
          $carrier = $data['carriers'];

        $order = new Order();
        $order->setCreatedAt(new \DateTime());
        $order->setState(1);
        $order->setCarrierName($carrier->getName());
        $order->setCarrierPrice($carrier->getPrice());
        $order->setDelivery($deliveryAddress->getFullAddressHtmlFormatted());
        foreach ($products as $product) {
          $orderDetail = new OrderDetail();
          $orderDetail->setProductName($product['object']->getName());
          $orderDetail->setProductIllustration($product['object']->getIllustration());
          $orderDetail->setProductPrice($product['object']->getPrice());
          $orderDetail->setProductTva($product['object']->getTva());
          $orderDetail->setProductQuantity($product['qty']);

          $order->addOrderDetail($orderDetail);
        }

        $entityManager->persist($order);
        $entityManager->flush();
      }

        return $this->render('order/summary.html.twig', [
          'choices' => $form->getData(),
          'cart' => $products,
          'totalCartWtprice' => $cart->getTotalWt(),
        ]);
    }
}
