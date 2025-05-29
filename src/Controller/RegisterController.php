<?php

namespace App\Controller;

use App\Form\RegisterUserType;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;

use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\SecurityBundle\Security;

final class RegisterController extends AbstractController
{
    #[Route('/inscription', name: 'app_register')]
    public function index(Request $request, EntityManagerInterface $entityManager, Security $security): Response
    {
      $user = new User();
      $form = $this->createForm(RegisterUserType::class, $user);

      $form->handleRequest($request); // merci d'écouter ce que l'utilisateur envoit.

      if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->persist($user); // Fige les données en lien avec l'entité
        $entityManager->flush(); // Enregistre les données en base de données
        $this->addFlash('success', 'Inscription réussie !');
        
        // connexion automatique de l'utilisateur après l'inscription
        $security->login($user);
        return $this->redirectToRoute('app_account');
      }

      return $this->render('register/index.html.twig', [
        'registerForm' => $form->createView(),
      ]);
    }
}
