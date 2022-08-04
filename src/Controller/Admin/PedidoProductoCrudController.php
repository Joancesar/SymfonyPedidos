<?php

namespace App\Controller\Admin;

use App\Entity\PedidoProducto;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class PedidoProductoCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return PedidoProducto::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
