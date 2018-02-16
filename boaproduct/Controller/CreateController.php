<?php

namespace App\Boa\ProductBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use App\Boa\ProductBundle\Form\Factory\FactoryInterface;
use App\Boa\ProductBundle\Model\ProductInterface;
use App\Boa\ProductBundle\Model\ProductManagerInterface;

use App\Boa\ProductBundle\Form\ProductType;

class CreateController extends Controller
{
    /**
     * @var FactoryInterface
     */
    private $formFactory;

    /**
     * @var ProductManagerInterface
     */
    private $productManager;

    /**
     * @param FactoryInterface              $formFactory
     * @param ProductManagerInterface       $productManager
     */
    public function __construct(FactoryInterface $formFactory, ProductManagerInterface $productManager)
    {
        $this->formFactory = $formFactory;
        $this->productManager = $productManager;
    }

    /**
     * @Route("/admin/products", name="admin_productlist")
     */
    public function productlist()
    {
        return $this->render('@BoaProduct/Admin/list.html.twig', array(
            'productsActive' => true
        ));
    }

    /**
     * @Route("/admin/product/create", name="admin_productcreate")
     */
    public function productcreate(Request $request)
    {
        $productManager = $this->productManager;

        $product = $productManager->createProduct();

        $form = $this->formFactory->createForm();
        $form->setData($product);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();

            $this->addFlash(
                'success',
                'Product succesvol toegevoegd!'
            );
        }

        return $this->render('@BoaProduct/Admin/create.html.twig', array(
            'form' => $form->createView(),
            'productsActive' => true
        ));
    }
}