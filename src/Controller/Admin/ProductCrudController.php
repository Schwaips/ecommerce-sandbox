<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use BcMath\Number;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name'),
            SlugField::new('slug')
                ->setRequired(true)
                ->setLabel('URL')
                ->setHelp('Le slug est utilisé dans l\'URL pour identifier le produit. Il doit être unique et ne contenir que des caractères alphanumériques, des tirets ou des underscores.')
                ->setTargetFieldName('name'),
            TextEditorField::new('description')
                ->setRequired(true)
                ->setLabel('Description'),
            ImageField::new('illustration')
                ->setRequired(true)
                ->setLabel('Illustration')
                ->setHelp('L\'illustration du produit doit être une image au format JPEG ou PNG en 600x600')
                ->setBasePath('/uploads/products')
                ->setUploadDir('public/uploads/products'),
            NumberField::new('price')
                ->setLabel('Prix')
                ->setRequired(true)
                ->setHelp('Prix HT sans le sigle euro'),
            ChoiceField::new('tva')
                ->setLabel('Taux de TVA')
                ->setChoices([
                    '5.5%' => '5.5',
                    '10%' => '10',
                    '20%' => '20',
                ])
                ->setHelp('Taux de TVA applicable au produit'),
            AssociationField::new('category', 'Catégorie associée')
                ->setRequired(true)
                ->setHelp('Sélectionnez la catégorie à laquelle appartient le produit')
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Produit')
            ->setEntityLabelInPlural('Produits')
            ->setPageTitle(Crud::PAGE_INDEX, 'Gestion des produits')
            ->setPageTitle(Crud::PAGE_EDIT, 'Modifier un produit')
            ->setPageTitle(Crud::PAGE_NEW, 'Ajouter un produit')
            ->setPageTitle(Crud::PAGE_DETAIL, 'Détails du produit');
    }
}
