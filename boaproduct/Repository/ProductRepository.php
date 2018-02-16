<?php

namespace App\Boa\ProductBundle\Repository;

use App\Baert\AppBundle\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class ProductRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function findBySlugDQL($slug)
    {
        $params = ['slug' => $slug];

        $em = $this->getEntityManager();

        $dql = "SELECT p FROM use App\Baert\AppBundle\Entity\Product p
                WHERE p.slug = :slug";

        $query = $em->createQuery($dql);

        $query->setHint(
            \Doctrine\ORM\Query::HINT_CUSTOM_OUTPUT_WALKER,
            'Gedmo\\Translatable\\Query\\TreeWalker\\TranslationWalker'
        );

        $query->setParameters($params);

        $product = $query->getOneOrNullResult();

        if (!empty($product)) {
            return $product;
        }

        return null;
    }

    public function findAllWithDiscount() {
        $em = $this->getEntityManager();

        $dql = "SELECT p FROM use App\Baert\AppBundle\Entity\Product p
                WHERE p.discount > 0
                ORDER BY p.discount DESC";

        $query = $em->createQuery($dql);

        $query->setHint(
            \Doctrine\ORM\Query::HINT_CUSTOM_OUTPUT_WALKER,
            'Gedmo\\Translatable\\Query\\TreeWalker\\TranslationWalker'
        );

        return $query->execute();
    }
}
