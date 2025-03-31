<?php

namespace App\Controller;

use App\Form\RegisterUserType;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;

use Doctrine\ORM\EntityManagerInterface;

final class RegisterController extends AbstractController
{
    #[Route('/inscription', name: 'app_register')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
      $user = new User();
      $form = $this->createForm(RegisterUserType::class, $user);

      $form->handleRequest($request);

      if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->persist($user); // Fige les données en lien avec l'entité
        $entityManager->flush(); // Enregistre les données en base de données
        $this->addFlash('success', 'Inscription réussie !');
      }

      return $this->render('register/index.html.twig', [
        'registerForm' => $form->createView(),
      ]);
    }
}
