<?php

namespace App\Controller\Admin;

use App\Entity\Estados;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class EstadosCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Estados::class;
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
