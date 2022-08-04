<?php

namespace App\Controller\Admin;

use App\Entity\Categoria;
use App\Entity\Estados;
use App\Entity\Pedido;
use App\Entity\PedidoProducto;
use App\Entity\Producto;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        return parent::index();
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('App1Symfony');
    }

    public function configureMenuItems(): iterable
    {
        return [
            MenuItem::linktoDashboard('Dashboard', 'fa fa-home'),
            MenuItem::linkToCrud('Categorías', 'fas fa-tags', Categoria::class),
            MenuItem::linkToCrud('Productos', 'fas fa-cube', Producto::class),
            MenuItem::linkToCrud('Pedidos', 'fas fa-shopping-basket', Pedido::class),
            MenuItem::linkToCrud('PedidosProductos', 'fas fa-shopping-cart', PedidoProducto::class),
            MenuItem::linkToCrud('Estados', 'fas fa-check-square', Estados::class),
            MenuItem::linkToCrud('Usuarios', 'fas fa-user', User::class),
            ];
        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
    }
}
