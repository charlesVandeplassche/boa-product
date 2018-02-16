<?php

namespace App\Boa\ProductBundle\Model;

/**
 * Abstract Product Manager implementation which can be used as base class for your
 * concrete manager.
 *
 * @author Charles Vandeplassche
 */
abstract class ProductManager implements ProductManagerInterface
{
    public function __construct()
    {

    }

    /**
     * {@inheritdoc}
     */
    public function createProduct()
    {
        $class = $this->getClass();
        $product = new $class();

        return $product;
    }

    /**
     * {@inheritdoc}
     */
    public function findProductByProductname($productname)
    {
        return $this->findProductBy(array('name' => $productname));
    }
}
