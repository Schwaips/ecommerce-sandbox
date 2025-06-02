<?php

namespace App\Controller\Admin;

use App\Entity\Order;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class OrderCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Order::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
                ->setDisabled(true)
                ->setLabel('ID de la commande'),
            DateField::new('createdAt')
                ->setDisabled(true)
                ->setLabel('Date de création'),
            NumberField::new('state')
                ->setDisabled(true)
                ->setLabel('État de la commande')->setTemplatePath('admin/order/state.html.twig'),
            AssociationField::new('user')
                ->setRequired(true)
                ->setLabel('Utilisateur')
                ->setHelp('L\'utilisateur qui a passé la commande.'),
            TextField::new('carrierName')
                ->setRequired(true)
                ->setLabel('Transporteur')
                ->setHelp('Le nom du transporteur utilisé pour la livraison.'),
            NumberField::new('totalTva'),
            NumberField::new('totalWt')
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
      return $crud
        ->setEntityLabelInSingular('Commande')
        ->setEntityLabelInPlural('Commandes')
        ->setPageTitle(Crud::PAGE_INDEX, 'Gestion des commandes')
        ->setPageTitle(Crud::PAGE_DETAIL, 'Détails de la commande');
    }

    public function configureActions(Actions $actions): Actions
    {
      $show = Action::new('Afficher')->linkToCrudAction('show');

        return $actions
            ->add(Crud::PAGE_INDEX, $show)
            ->remove(Crud::PAGE_INDEX, Action::NEW)
            ->remove(Crud::PAGE_INDEX, Action::DELETE)
            ->remove(Crud::PAGE_INDEX, Action::EDIT);
    }

    public function show(AdminContext $context)
    {
        $order = $context->getEntity()->getInstance();

        return $this->render('admin/order/show.html.twig', [
            // 'order' => $order,
            // 'details' => $order->getOrderDetails(),
        ]);
    }
}
