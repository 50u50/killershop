<?php

namespace App\Api\Entity\Sales\Order;

use App\Api\Entity\Sales\Order;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Api\Repository\Sales\Order\ItemRepository")
 * @ORM\Table(name="sales_order_item")
 */
class Item
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=16)
     * @Assert\NotBlank()
     * @Assert\Length(min = 14, max = 16)
     */
    private $product_code;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Length(min = 1, max = 255)
     */
    private $product_name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Length(min = 1, max = 255)
     */
    private $product_brand;

    /**
     * @ORM\Column(type="float")
     * @Assert\Type(type = "float")
     * @Assert\Range(min = 0)
     */
    private $quantity;

    /**
     * @ORM\Column(type="decimal", precision=13, scale=2)
     * @Assert\Type(type = "float")
     * @Assert\Range(min = 0, max=99999999999.99)
     */
    private $subtotal;

    /**
     * @ORM\ManyToOne(targetEntity="App\Api\Entity\Sales\Order")
     * @ORM\JoinColumn(name="order_id", referencedColumnName="id", nullable=FALSE)
     */
    private $salesOrder;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProductCode(): ?string
    {
        return $this->product_code;
    }

    public function setProductCode(string $product_code): self
    {
        $this->product_code = $product_code;

        return $this;
    }

    public function getProductName(): ?string
    {
        return $this->product_name;
    }

    public function setProductName(string $product_name): self
    {
        $this->product_name = $product_name;

        return $this;
    }

    public function getProductBrand(): ?string
    {
        return $this->product_brand;
    }

    public function setProductBrand(string $product_brand): self
    {
        $this->product_brand = $product_brand;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getSubtotal()
    {
        return $this->subtotal;
    }

    public function setSubtotal($subtotal): self
    {
        $this->subtotal = $subtotal;

        return $this;
    }

    public function getSalesOrder():Order
    {
        return $this->salesOrder;
    }

    public function setSalesOrder(Order $salesOrder):self
    {
        $this->salesOrder = $salesOrder;

        return $this;
    }
}
