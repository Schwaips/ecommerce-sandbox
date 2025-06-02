<?php

namespace App\Controller;

use App\Form\OrderType;
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
         'addresses' => $addresses
       ]);

        return $this->render('order/index.html.twig', [
            'deliveryForm' => $form->createView(),
        ]);
    }
}
