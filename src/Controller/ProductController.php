<?php


namespace App\Controller;


use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\This;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{

    public function __construct()
    {
    }


    /**
     * @Route("/products", name="products")
     * @param Request $request
     * @param ProductRepository $repository
     * @return Response
     */
    public function show(Request $request, ProductRepository $repository)
    {
        $title = "Bshop";
        $announce = "Welcome to bshop";
        //dd($request->get('min_price'));
        $sort_by = $request->get('sort_by');
        $products = $repository->getByFilter($request->get('type'), $request->get('brand'), $request->get('size'), [$request->get('min_price'), $request->get('max_price')], $sort_by);


        /**
         * @var $product Product
         * @var $products Product[]
         *
         */
        foreach ($products as $id => $product){
            if(strlen($product->getDescription()) > 20){
                $products[$id]->setDescription(str_split($product->getDescription(), 20)[0]."...");
            };
        }

        return $this->render('/pages/products.html.twig', [
            'title' => $title,
            'announce' => $announce,
            'products' => $products,
            'sizes' => $repository->getFilterOf("size"),
            'brands' => $repository->getFilterOf("brand"),
            'types' => $repository->getFilterOf("type"),
            'prices' => $repository->getFilterOf("price"),
        ]);
    }

    /**
     * @Route("/detail", name="detail")
     * @param Request $request
     * @param ProductRepository $repository
     * @return Response
     */
    public function detail(Request $request, ProductRepository $repository)
    {
        $id = $request->get('id');
        $product = $repository->find($id);
        return $this->render('/pages/detail.html.twig',[
            'product' => 'product'
        ]);
    }
}