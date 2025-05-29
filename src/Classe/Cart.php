<?php


namespace App\Classe;

use Symfony\Component\HttpFoundation\RequestStack;

class Cart {

  public function __construct(private RequestStack $requestStack)
  {}

  public function getCart() {
    $session = $this->requestStack->getSession();
    if (!$session->has('cart')) {
      return [];
    }
    return $session->get('cart');
  }

  public function add($product) {
    $cart = $this->requestStack->getSession()->get('cart');

    $this->requestStack->getSession()->set('cart', []);
    if(isset($cart[$product->getId()])) {
      $cart[$product->getId()]['qty']++;
    } else {
      $cart[$product->getId()] = [
        'object' => $product,
        'qty' => 1,
      ];
    }
    

    $this->requestStack->getSession()->set('cart', $cart);
  }

  public function removeAllProducts() {
    return $this->requestStack->getSession()->remove('cart');
  }
}