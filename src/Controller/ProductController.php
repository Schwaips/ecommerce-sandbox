<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ProductController extends AbstractController
{
    #[Route('/product', name: 'app_product')]
    public function index(): Response
    {
        return $this->render('product/index.html.twig', [
            'controller_name' => 'ProductController',
        ]);
    }

    #[Route('/produit/{slug}', name: 'app_product_detail')]
    public function show($slug, ProductRepository $productRepository): Response
    {

      $product = $productRepository->findOneBySlug($slug);
        // Here you would typically fetch the product from the database using the ID.
        // For now, we'll just return a placeholder response.

        return $this->render('product/show.html.twig', [
            'product' => $product, // Placeholder for product name
        ]);
    }
}
