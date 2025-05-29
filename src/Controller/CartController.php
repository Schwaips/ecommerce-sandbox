<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CartController extends AbstractController
{
  #[Route('/mon-panier', name: 'app_cart')]
  public function index(Cart $cart): Response
  {

    $cart = $cart->getCart();


    return $this->render('cart/index.html.twig', [
      'cart' => $cart
    ]);
  }


  #[Route('/cart/add/{product_id}', name: 'app_cart_add')]
  public function add($product_id, Cart $cart, ProductRepository $productRepository): Response
  {
    $product = $productRepository->findOneById($product_id);
    
    $cart->add($product);


    $this->addFlash('success', 'Le produit a bien été ajouté au panier');
    return $this->redirectToRoute('app_product_show', [
      'slug' => $product->getSlug(),
    ]);
  }

  #[Route('/cart/remove_all_product', name: 'app_cart_remove_all_product')]
  public function removeAllProduct(Cart $cart): Response
  {
    $cart->removeAllProducts();


    $this->addFlash('success', 'Panier vidé');
    return $this->redirectToRoute('app_cart');
  }
  
}
