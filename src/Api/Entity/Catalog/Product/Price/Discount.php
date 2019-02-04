<?php

namespace App\Api\Entity\Catalog\Product\Price;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Api\Repository\Catalog\Product\Price\DiscountRepository")
 * @ORM\Table(name="product_price_discount")
 * @todo  add is_active?
 */
class Discount
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="App\Api\Entity\Catalog\Product\Price")
     * @ORM\JoinColumn(name="price_id", referencedColumnName="id", nullable=FALSE)
     */
    private $price;

    /**
     * @ORM\Column(type="string", length=16)
     * @Assert\NotBlank()
     * @Assert\Length(min = 1, max = 16)
     */
    private $rule;

    /**
     * @ORM\Column(type="decimal", precision=13, scale=2)
     * @Assert\NotBlank()
     * @Assert\Type(type = "float")
     * @Assert\Range(min = 0, max=99999999999.99)
     */
    private $value;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrice(): ?\App\Api\Entity\Catalog\Product\Price
    {
        return $this->price;
    }

    public function setPrice(?\App\Api\Entity\Catalog\Product\Price $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getRule(): ?string
    {
        return $this->rule;
    }

    public function setRule(string $rule): self
    {
        $this->rule = $rule;

        return $this;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function setValue($value): self
    {
        $this->value = $value;

        return $this;
    }
}
