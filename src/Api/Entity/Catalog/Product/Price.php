<?php

namespace App\Api\Entity\Catalog\Product;

use App\Api\Entity\Catalog\Product as ProductEntity;
use App\Api\Entity\Catalog\Product\Price\Discount;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Api\Repository\Catalog\Product\PriceRepository")
 * @ORM\Table(name="product_price",
 *     uniqueConstraints={
 *       @ORM\UniqueConstraint(
 *          name="product_currency_price",
 *          columns={"currency", "product_id"}
 *          )
 *     },
 *     indexes={
 *          @ORM\Index(name="base_price_idx", columns={"base_price"})
 *     }
 * )
 */
class Price
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=3)
     * @Assert\NotBlank()
     * @Assert\Length(min = 3, max = 3)
     */
    private $currency;

    /**
     * @ORM\Column(type="decimal", precision=13, scale=2)
     * @Assert\NotBlank()
     * @Assert\Type(type = "float")
     * @Assert\Range(min = 0, max=99999999999.99)
     */
    private $base_price;
    /**
     * @ORM\OneToOne(
     *     targetEntity="\App\Api\Entity\Catalog\Product\Price\Discount",
     *     mappedBy="price",
     *     cascade={"persist", "remove"}
     * )
     */
    private $discount;

    /**
     * @ORM\OneToOne(targetEntity="App\Api\Entity\Catalog\Product")
     */
    private $product;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProduct(): ?ProductEntity
    {
        return $this->product;
    }

    public function setProduct(ProductEntity $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): self
    {
        $this->currency = $currency;

        return $this;
    }

    public function getBasePrice()
    {
        return $this->base_price;
    }

    public function setBasePrice(float $base_price): self
    {
        $this->base_price = $base_price;

        return $this;
    }

    public function getDiscount(): ?Discount
    {
        return $this->discount;
    }

    public function setDiscount(Discount $discount): self
    {
        $this->discount = $discount;

        return $this;
    }
}
