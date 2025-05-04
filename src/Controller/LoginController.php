<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

final class LoginController extends AbstractController
{
    #[Route('/connexion', name: 'app_login')]
    public function index(AuthenticationUtils $authenticationUtils): Response
    {

      $error = $authenticationUtils->getLastAuthenticationError(); 
      $lastUsername = $authenticationUtils->getLastUsername();  // email, si l'utiilisateur se trompe d'email.

        return $this->render('login/index.html.twig', [
          'last_username' => $lastUsername, // email 
          'error' => $error,
        ]);
    }
}
