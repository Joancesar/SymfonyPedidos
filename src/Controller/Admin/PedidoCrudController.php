<?php

namespace App\Controller\Admin;

use App\Entity\Pedido;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class PedidoCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Pedido::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            DateTimeField::new('fecha'),
            AssociationField::new('user'),
            AssociationField::new('estado'),
            AssociationField::new('pedidoProductos'),
        ];
    }

}
