<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CartController extends AbstractController
{
  #[Route('/mon-panier', name: 'app_cart')]
  public function index(Cart $cart): Response
  {

    return $this->render('cart/index.html.twig', [
      'cart' => $cart->getCart(),
      'totalWtprice' => $cart->getTotalWt(),
      'totalExclTaxePrice' => $cart->getTotalExclTaxes(),
    ]);
  }

  #[Route('/cart/add/{product_id}', name: 'app_cart_add')]
  public function add(
    $product_id, 
    Cart $cart, 
    ProductRepository $productRepository, 
    Request $request
    ): Response
  {
    $product = $productRepository->findOneById($product_id);
    $refererUrl = $request->headers->get('referer');
    
    $cart->add($product);

    $this->addFlash('success', 'Le produit a bien été ajouté au panier');
    return $this->redirect($refererUrl);
  }

  #[Route('/cart/decrease/{product_id}', name: 'app_cart_decrease')]
  public function decrease($product_id, Cart $cart): Response
  {
    
    $cart->decrease($product_id);
    $this->addFlash('success', 'Le produit a bien été retiré du panier');

    return $this->redirectToRoute('app_cart');
  }

  #[Route('/cart/remove_all_product', name: 'app_cart_remove_all_product')]
  public function removeAllProduct(Cart $cart): Response
  {
    $cart->removeAllProducts();


    $this->addFlash('success', 'Panier vidé');
    return $this->redirectToRoute('app_cart');
  }
  
}
