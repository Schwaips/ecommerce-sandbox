<?php


namespace App\Classe;

use Symfony\Component\HttpFoundation\RequestStack;

class Cart {

  public function __construct(private RequestStack $requestStack)
  {}

  /**
   * Get the current cart from the session.
   * @return array
   */
  public function getCart() {
    $session = $this->requestStack->getSession();
    if (!$session->has('cart')) {
      return [];
    }
    return $session->get('cart');
  }
  
  /**
  * Add a product to the cart.
  *
  * @param Product $product The product to add.
  */
  public function add($product) {
    $cart = $this->getCart();

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

  /**
   * Decrease the quantity of a product in the cart.
   *
   * @param int $product_id The ID of the product to decrease.
   */
  public function decrease($product_id) {
    $cart = $this->getCart();

    if($cart[$product_id]['qty'] > 1) {
      $cart[$product_id]['qty']--;    
    } else {
      unset($cart[$product_id]); # remove key 
    }

    $this->requestStack->getSession()->set('cart', $cart);
  }

  /**
   * Remove all products from the cart.
   *
   * @return void
   */
  public function removeAllProducts() {
    return $this->requestStack->getSession()->remove('cart');
  }

  public function fullQuantity() {
    $cart = $this->getCart();
    if (!isset($cart)) {
      return 0;
    }

    $totalQuantity = 0;
    foreach ($cart as $item) {
      $totalQuantity += $item['qty'];
    }
    
    return $totalQuantity;
  }

  /**
   * Get the total price of the cart including tax.
   *
   * @return float
   */
  public function getTotalWT() {
    $cart = $this->getCart();
    if (!isset($cart)) {
      return 0;
    }

    $total = 0;
    foreach ($cart as $item) {
      $total += $item['object']->getPriceWt() * $item['qty'];
    }
    
    return $total;
  }

  public function getTotalExclTaxes() {
    $cart = $this->getCart();
    if (!isset($cart)) {
      return 0;
    }

    $total = 0;
    foreach ($cart as $item) {
      $total += $item['object']->getPrice() * $item['qty'];
    }
    
    return $total;
  }
}