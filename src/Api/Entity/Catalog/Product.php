<?php

namespace App\Api\Entity\Catalog;

use App\Api\Entity\Catalog\Product\Relation;
use App\Api\Interfaces\DiscountableInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Api\Repository\Catalog\ProductRepository")
 * @ORM\Table(name="product",
 *     uniqueConstraints={
 *       @ORM\UniqueConstraint(
 *          name="product_code", columns={"code"})
 *     },
 *     indexes={
 *          @ORM\Index(name="name_idx", columns={"name"}),
 *          @ORM\Index(name="brand_idx", columns={"brand"})
 *     }
 * )
 *
 * @todo add is_active, is_bundle?
 */

class Product implements DiscountableInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * Store GTIN(14) or some internal numbers
     * @ORM\Column(type="string", length=16)
     * @Assert\NotBlank()
     * @Assert\Length(min = 14, max = 16)
     */
    private $code;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Length(min = 1, max = 255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Length(min = 1, max = 255)
     */
    private $brand;

    /**
     * @ORM\OneToMany(
     *     targetEntity="App\Api\Entity\Catalog\Product\Relation",
     *     mappedBy="parent",
     *     cascade={"persist", "remove"}
     * )
     */
    private $relations;

    /**
     * @ORM\OneToOne(
     *     targetEntity="App\Api\Entity\Catalog\Product\Price",
     *     mappedBy="product",
     *     cascade={"persist", "remove"}
     * )
     */
    private $price;

    /**
     * @var float
     */
    private $discountPrice;

    public function __construct()
    {
        $this->relations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getBrand(): ?string
    {
        return $this->brand;
    }

    public function setBrand(string $brand): self
    {
        $this->brand = $brand;

        return $this;
    }

    /**
     * @return Relation[]
     */
    public function getRelations()
    {
        return $this->relations;
    }

    public function setRelations(array $relations)
    {
        $this->relations = $relations;

        return $this;
    }

    public function getPrice(): ?Product\Price
    {
        return $this->price;
    }

    public function setPrice(Product\Price $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getBasePrice(): ?float
    {
        if (!$this->getPrice()) {
            return null;
        }

        return $this
            ->getPrice()
            ->getBasePrice();
    }

    public function getDiscountValue(): ?float
    {
        if (!$this->getPrice() || !$this->getPrice()->getDiscount()) {
            return null;
        }

        return $this
            ->getPrice()
            ->getDiscount()
            ->getValue();
    }

    public function getDiscountRule(): ?string
    {
        if (!$this->getPrice() || !$this->getPrice()->getDiscount()) {
            return null;
        }

        return $this
            ->getPrice()
            ->getDiscount()
            ->getRule();
    }

    public function getDiscountPrice(): ?float
    {
        return $this->discountPrice;
    }

    public function setDiscountPrice(float $discountPrice): self
    {
        $this->discountPrice = $discountPrice;

        return $this;
    }
}
