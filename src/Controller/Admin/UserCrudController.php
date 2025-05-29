<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('email')->setRequired(true)->setLabel('Adresse e-mail'),
            TextField::new('firstName')->setRequired(true)->setLabel('Prénom'),
            TextField::new('lastName')->setRequired(true)->setLabel('Nom')
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
      return $crud
        ->setEntityLabelInSingular('Utilisateur')
        ->setEntityLabelInPlural('Utilisateurs')
        ->setPageTitle(Crud::PAGE_INDEX, 'Gestion des utilisateurs')
        ->setPageTitle(Crud::PAGE_EDIT, 'Modifier un utilisateur')
        ->setPageTitle(Crud::PAGE_NEW, 'Ajouter un utilisateur')
        ->setPageTitle(Crud::PAGE_DETAIL, 'Détails de l\'utilisateur');
    }
}
