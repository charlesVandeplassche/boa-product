<?php

namespace App\Boa\ProductBundle\Model;

/**
 * Interface to be implemented by product managers. This adds an additional level
 * of abstraction between your application, and the actual repository.
 *
 * All changes to products should happen through this interface.
 *
 * @author Charles Vandeplassche
 */
interface ProductManagerInterface
{
    /**
     * Creates an empty product instance.
     *
     * @return ProductInterface
     */
    public function createProduct();

    /**
     * Deletes a product.
     *
     * @param ProductInterface $product
     */
    public function deleteProduct(ProductInterface $product);

    /**
     * Finds one product by the given criteria.
     *
     * @param array $criteria
     *
     * @return ProductInterface|null
     */
    public function findProductBy(array $criteria);

    /**
     * Find a product by its name.
     *
     * @param string $productname
     *
     * @return ProductInterface|null
     */
    public function findProductByProductname($productname);

    /**
     * Returns a collection with all product instances.
     *
     * @return \Traversable
     */
    public function findProducts();

    /**
     * Returns the product's fully qualified class name.
     *
     * @return string
     */
    public function getClass();

    /**
     * Reloads a product.
     *
     * @param ProductInterface $product
     */
    public function reloadProduct(ProductInterface $product);

    /**
     * Updates a product.
     *
     * @param ProductInterface $product
     */
    public function updateProduct(ProductInterface $product);
}
