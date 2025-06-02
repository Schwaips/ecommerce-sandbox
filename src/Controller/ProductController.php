<?php

namespace App\Controller;

use App\Entity\Product;
// use App\Repository\ProductRepository;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ProductController extends AbstractController
{
  // public function show($slug, ProductRepository $productRepository): Response
  #[Route('/produit/{slug}', name: 'app_product_show')]
  public function show(#[MapEntity(mapping: ['slug' => 'slug'])] Product $product): Response
  {
    // $product = $productRepository->findOneBy(['slug' => $slug]);
    if (!$product) {
      return $this->redirectToRoute('app_home');
    }

      return $this->render('product/show.html.twig', [
          'product' => $product, // Placeholder for product name
      ]);
  }
}
