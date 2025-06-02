<?php

namespace App\Controller\Account;

use App\Form\PasswordUserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;


final class PasswordController extends AbstractController {
  private $entityManager;
  
  public function __construct(EntityManagerInterface $entityManager)
  {
    $this->entityManager = $entityManager;
  }

  #[Route('/compte/modifier-pwd', name: 'app_account_modify_pwd')]
  public function index(Request $request, UserPasswordHasherInterface $userPasswordHasher): Response
  {
    $user = $this->getUser();

    $form = $this->createForm(PasswordUserType::class,  $user, [
        'userPasswordHasher' => $userPasswordHasher,
    ]);

    $form->handleRequest($request);

    if($form->isSubmitted() && $form->isValid()) {
      $this->entityManager->flush();
      $this->addFlash('success', 'Votre mot de passe a été modifié avec succès !');
      return $this->redirectToRoute('app_account');
    }
    return $this->render('account/password/index.html.twig', [
      'passwordForm' => $form->createView(),
    ]);
  }

}