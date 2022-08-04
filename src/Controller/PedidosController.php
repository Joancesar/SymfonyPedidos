<?php

namespace App\Controller;

use App\Entity\Categoria;
use App\Entity\Estados;
use App\Entity\Pedido;
use App\Entity\PedidoProducto;
use App\Entity\Producto;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class PedidosController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(): Response {

        $productos = $this->getDoctrine()->getRepository(Producto::class)->findBy([],[], 12);

        return $this->render('pedidos/index.html.twig', [
            'productos' => $productos
        ]);
    }

    /**
     * @Route("/categorias/{nombre}", name="categorias")
     */
    public function categorias($nombre, Request $request): Response {
        $id = $request->get('id');
        $categoria = $this->getDoctrine()->getRepository(Categoria::class)->find($id);

        return $this->render('pedidos/categorias.html.twig', [
            'nombre' => $categoria->getNombre(),
            'productos' => $categoria->getProductos(),
        ]);
    }
    /**
     * @Route("/productos/{nombre}", name="productos")
     */
    public function productos($nombre, Request $request) {
        $id = $request->get('id');//Recogemos el id enviado por Post
        $producto = $this->getDoctrine()->getRepository(Producto::class)->find($id);

        return $this->render('pedidos/productos.html.twig', [
            'producto' => $producto,
        ]);
    }

    /**
     * @Route("/carrito", name="carrito")
     */
    public function carrito(SessionInterface $session, Request $request): Response {
        $productos = [];
        $carrito = $session->get('carrito') ?? [];
        $total = 0;

        foreach ($carrito as $codigo => $cantidad){
            $producto = $this->getDoctrine()
                ->getRepository(Producto::class)
                ->find($codigo);
            $elem = [];
            $elem['imagen'] = $producto->getImagenProducto();
            $elem['nombre'] = $producto->getNombre();
            $elem['precio'] = $producto->getPrecio();
            $elem['id'] = $producto->getId();
            $elem['unidades'] = $cantidad['unidades'];
            $total+=$elem['unidades']*$elem['precio'];
            $productos[] = $elem;
        }

        return $this->render('pedidos/carrito.html.twig',[
            'productos' => $productos,
            'total' => $total,
        ]);
    }

    /**
     * @Route("/anadir", name="anadir")
     */
    public function anadir(SessionInterface $session, Request $request) {
        $carrito = $session->get('carrito') ?? [];
        $totalUnidades = $session->get('totalUnidades') ?? 0;

        $id = $request->get('id'); //Recogemos el id enviado por Post
        $unidades = $request->get('unidades');

        $carrito[$id]['unidades'] = $carrito[$id]['unidades'] ?? 0 + intval($unidades);
        $totalUnidades += intval($unidades);

        $session->set('carrito', $carrito);
        $session->set('totalUnidades', $totalUnidades);
        return $this->redirectToRoute('carrito');
    }

    /**
     * @Route("/eliminar", name="eliminar")
     */
    public function eliminar(SessionInterface $session, Request $request) {
        $carrito = $session->get('carrito') ?? [];
        $totalUnidades = $session->get('totalUnidades') ?? 0;
        $id = $request->get('id'); //Recogemos el id enviado por Post

        $totalUnidades -= $carrito[$id]['unidades'];
        unset($carrito[$id]);

        $session->set('carrito', $carrito);
        $session->set('totalUnidades', $totalUnidades);

        return $this->redirectToRoute('carrito');
    }

    /**
     * @Route("/actualizar", name="actualizar")
     */
    public function actualizar(SessionInterface $session, Request $request) {
        $carrito = $session->get('carrito') ?? [];
        $totalUnidades = $session->get('totalUnidades') ?? 0;

        //Recogemos el id enviado por Post
        $id = $request->get('id');
        $unidades = $request->get('unidades');

        $totalUnidades-=$carrito[$id]['unidades'];
        $carrito[$id]['unidades'] = intval($unidades);
        $totalUnidades+=$carrito[$id]['unidades'];

        $session->set('totalUnidades', $totalUnidades);
        $session->set('carrito', $carrito);
        return $this->redirectToRoute('carrito');
    }

    /**
     * @Route("/realizarPedido", name="realizarPedido")
     */
    public function realizarPedido(SessionInterface $session, Request $request): Response {
        $carrito = $session->get('carrito') ?? [];

        $pedido = new Pedido();
        $pedido->setFecha(new \DateTime());
        $estado = $this->getDoctrine()->getRepository(Estados::class)->find(1);
        $pedido->setEstado($estado);
        $pedido->setUser($this->getUser());
        $this->getDoctrine()->getManager()->persist($pedido);

        foreach ($carrito as $codigo => $cantidad){
            $producto = $this->getDoctrine()
                ->getRepository(Producto::class)
                ->find($codigo);

            //Comprobamos si hay stock
            if(implode($cantidad) > $producto->getStock()) {
                $this->addFlash('error',
                    "Error: Actualmente no disponemos de la cantidad que solicita de 
                        {$producto->getNombre()}(Cantidad actual: {$producto->getStock()})");
                return $this->redirectToRoute('carrito');
            }

            $pedidoProducto = new PedidoProducto();
            $pedidoProducto->setCodProd($producto);
            $pedidoProducto->setUnidades(implode($cantidad));
            $pedidoProducto->setCodPed($pedido);
            $this->getDoctrine()->getManager()->persist($pedidoProducto);

            //Actualizamos stock
            $producto->setStock($producto->getStock()-implode($cantidad));
            $this->getDoctrine()->getManager()->flush();
        }
        //Vaciamos carrito
        $session->set('carrito', []);
        $session->set('totalUnidades', 0);
        $this->addFlash('success',
            "Tu pedido ha sido confirmado. Revisa tu email para mas detalles.");

        return $this->redirectToRoute('misPedidos');
    }


    /**
     * @Route("/mis-pedidos", name="misPedidos")
     */
    public function misPedidos(Request $request) {

        $show = $request->query->get('show');
        return $this->render('pedidos/misPedidos.html.twig', ['show' => $show]);
    }


    public function allCategorias(): Response {
        $categorias = $this->getDoctrine()->getRepository(Categoria::class)->findAll();

        return $this->render('pedidos/_navbar_categorias.html.twig', [
            'categorias' => $categorias
        ]);
    }
}
