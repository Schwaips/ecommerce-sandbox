<?php

namespace App\Controller\Account;

use App\Entity\Address;
use App\Repository\AddressRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Form\AddressUserType;

final class AddressController extends AbstractController {
  private $entityManager;
  
  public function __construct(EntityManagerInterface $entityManager)
  {
    $this->entityManager = $entityManager;
  }

  #[Route('/compte/adresses', name: 'app_account_addresses')]
  public function index(): Response
  {
      return $this->render('account/address/index.html.twig');
  }


  #[Route('/compte/adresse/ajouter/{id}', name: 'app_account_addresses_form', defaults: ['id' => null])]
  public function form(Request $request, $id, AddressRepository $addressRepository): Response
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

    return $this->render('account/address/form.html.twig', [
      'addressForm' => $form->createView(),
    ]);
  }

  #[Route('/compte/adresses/delete/{id}', name: 'app_account_addresses_delete')]
  public function delete($id, AddressRepository $addressRepository): Response
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