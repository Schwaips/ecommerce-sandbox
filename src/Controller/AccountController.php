<?php

namespace App\Controller;

use App\Form\PasswordUserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

final class AccountController extends AbstractController
{
    #[Route('/compte', name: 'app_account')]
    public function index(): Response
    {
        return $this->render('account/index.html.twig');
    }

    #[Route('/compte/modifier-pwd', name: 'app_account_modify_pwd')]
    public function password(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
      $user = $this->getUser();

      $form = $this->createForm(PasswordUserType::class,  $user, [
          'userPasswordHasher' => $userPasswordHasher,
      ]);

      $form->handleRequest($request);

      if($form->isSubmitted() && $form->isValid()) {
        $entityManager->flush();
        $this->addFlash('success', 'Votre mot de passe a été modifié avec succès !');
        return $this->redirectToRoute('app_account');
      }
      return $this->render('account/password.html.twig', [
        'passwordForm' => $form->createView(),
      ]);
    }
}
