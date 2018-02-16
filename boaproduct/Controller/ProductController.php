<?php

namespace App\Boa\ProductBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Baert\AppBundle\Entity\Product;

class ProductController extends Controller
{
    /**
     * @Route(
     *     "/{_locale}/products/{slug}",
     *     defaults={"_format": "html"},
     *     requirements={
     *         "_locale": "nl|en|fr"
     *     }
     * )
     */
    public function show($slug)
    {
        $product = $this->getDoctrine()
            ->getRepository(Product::class)
            ->findBySlugDQL($slug);

        return $this->render('subtemplates/product/detail.html.twig', array(
            'product' => $product
        ));
    }

    public function discountProducts($max = 4)
    {
        $discountproducts = $this->getDoctrine()
            ->getRepository(Product::class)
            ->findAllWithDiscount();

        return $this->render('@BoaProduct/Product/list.html.twig', array(
            'products' => $discountproducts
        ));
    }
}