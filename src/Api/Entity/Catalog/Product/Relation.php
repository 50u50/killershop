<?php

namespace App\Api\Entity\Catalog\Product;

use App\Api\Entity\Catalog\Product as ProductEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Api\Repository\Catalog\Product\RelationRepository")
 * @ORM\Table(name="product_relation")
 */
class Relation
{
    const MIN_BUNDLE_SIZE = 2;
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="App\Api\Entity\Catalog\Product")
     */
    private $parent;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="App\Api\Entity\Catalog\Product")
     */
    private $product;

    public function setParent(ProductEntity $parent)
    {
        $this->parent = $parent;

        return $this;
    }

    public function getParent(): ?ProductEntity
    {
        return $this->parent;
    }

    public function setProduct(ProductEntity $product)
    {
        $this->product = $product;

        return $this;
    }

    public function getProduct(): ?ProductEntity
    {
        return $this->product;
    }

    public function getRelationNames()
    {
        $res = [];
        foreach ($this->product as $pr) {
            $res[] = $pr;
        }

        return $res;
    }
}
