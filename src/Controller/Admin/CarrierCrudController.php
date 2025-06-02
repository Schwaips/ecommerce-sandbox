<?php

namespace App\Controller\Admin;

use App\Entity\Carrier;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class CarrierCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Carrier::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name')
                ->setRequired(true)
                ->setLabel('Nom du transporteur'),
            TextareaField::new('description')
                ->setRequired(true)
                ->setLabel('Description'),
            NumberField::new('price')
                ->setRequired(true)
                ->setLabel('Prix')
                ->setHelp('Le prix du transporteur en euros.'),
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Transporteur')
            ->setEntityLabelInPlural('Transporteurs')
            ->setPageTitle(Crud::PAGE_INDEX, 'Gestion des transporteurs')
            ->setPageTitle(Crud::PAGE_EDIT, 'Modifier un transporteur')
            ->setPageTitle(Crud::PAGE_NEW, 'Ajouter un transporteur')
            ->setPageTitle(Crud::PAGE_DETAIL, 'Détails de la catégorie');
    }
}
