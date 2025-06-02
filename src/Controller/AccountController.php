<?php

namespace App\Controller;

use App\Entity\Address;
use App\Form\AddressUserType;
use App\Form\PasswordUserType;
use App\Repository\AddressRepository;
use Doctrine\ORM\EntityManagerInterface;
use Dom\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

final class AccountController extends AbstractController
{
  private $entityManager;
  
  public function __construct(EntityManagerInterface $entityManager)
  {
    $this->entityManager = $entityManager;
  }

    #[Route('/compte', name: 'app_account')]
    public function index(): Response
    {
        return $this->render('account/index.html.twig');
    }

    #[Route('/compte/modifier-pwd', name: 'app_account_modify_pwd')]
    public function password(Request $request, UserPasswordHasherInterface $userPasswordHasher): Response
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
      return $this->render('account/password.html.twig', [
        'passwordForm' => $form->createView(),
      ]);
    }

    #[Route('/compte/adresses', name: 'app_account_addresses')]
    public function addresses(): Response
    {
        return $this->render('account/addresses.html.twig');
    }


    #[Route('/compte/adresse/ajouter/{id}', name: 'app_account_addresses_form', defaults: ['id' => null])]
    public function addressForm(Request $request, $id, AddressRepository $addressRepository): Response
    {

      if($id) {
        $address = $addressRepository->findOneById($id);
        if (!$address || $address->getUser() !== $this->getUser()) {
          $this->addFlash('error', 'Adresse non trouvée ou vous n\'êtes pas autorisé à la modifier.');
          return $this->redirectToRoute('app_account_addresses');
        }

      } else {
        $address = new Address();
        $address->setUser($this->getUser());
      }

      $form = $this->createForm(AddressUserType::class, $address);
      $form->handleRequest($request);

      if($form->isSubmitted() && $form->isValid()) {
        $this->entityManager->persist($address);
        $this->entityManager->flush();
        $this->addFlash('success', 'Votre adresse a été ajoutée avec succès !');

        return $this->redirectToRoute('app_account_addresses');
      }

      return $this->render('account/address_form.html.twig', [
        'addressForm' => $form->createView(),
      ]);
    }

    #[Route('/compte/adresses/delete/{id}', name: 'app_account_addresses_delete')]
    public function addressDelete($id, AddressRepository $addressRepository): Response
    {
      $address = $addressRepository->findOneById($id);

      if (!$address || $address->getUser() !== $this->getUser()) {
        $this->addFlash('error', 'Adresse non trouvée ou vous n\'êtes pas autorisé à la supprimer.');
        return $this->redirectToRoute('app_account_addresses');
      }

      $this->entityManager->remove($address);
      $this->entityManager->flush();
      $this->addFlash('success', 'Votre adresse a été supprimée avec succès !');
      return $this->redirectToRoute('app_account_addresses');
    }
}
