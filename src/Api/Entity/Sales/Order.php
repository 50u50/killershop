<?php

namespace App\Api\Entity\Sales;

use App\Api\Entity\Sales\Order\Item;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Api\Repository\Sales\OrderRepository")
 * @ORM\Table(name="sales_order",
 *   indexes={
 *     @ORM\Index(name="customer_email_idx", columns={"customer_email"}),
 *     @ORM\Index(name="submitted_idx", columns={"submitted"}),
 *     @ORM\Index(name="total_idx", columns={"currency", "total"}),
 *     @ORM\Index(name="status_idx", columns={"status"})
 *     }
 * )
 */
class Order
{
    const STATUS_SUBMITTED = 'submitted';
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Email()
     * @Assert\Length(max = 255)
     */
    private $customer_email;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     * @Assert\DateTime()
     */
    private $submitted;

    /**
     * @ORM\Column(type="decimal", precision=13, scale=2)
     * @Assert\Type(type = "float")
     * @Assert\Range(min = 0, max=99999999999.99)
     */
    private $total;

    /**
     * @ORM\Column(type="string", length=3)
     * @Assert\NotBlank()
     * @Assert\Length(min = 3, max = 3)
     */
    private $currency;

    /**
     * @ORM\Column(type="string", length=16)
     * @Assert\NotBlank()
     * @Assert\Length(min = 1, max = 16)
     */
    private $status;

    /**
     * @ORM\OneToMany(
     *     targetEntity="App\Api\Entity\Sales\Order\Item",
     *     mappedBy="salesOrder",
     *     cascade={"persist"}
     * )
     * @var Item[]
     */
    private $items;

    public function __construct()
    {
        $this->items = new ArrayCollection();
        $this->submitted = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCustomerEmail(): ?string
    {
        return $this->customer_email;
    }

    public function setCustomerEmail(string $customer_email): self
    {
        $this->customer_email = $customer_email;

        return $this;
    }

    public function getSubmitted(): ?\DateTime
    {
        return $this->submitted;
    }

    public function setSubmitted(\DateTime $submitted): self
    {
        $this->submitted = $submitted;

        return $this;
    }

    public function getTotal()
    {
        return $this->total;
    }

    public function setTotal($total): self
    {
        $this->total = $total;

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

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Item[]
     */
    public function getItems()
    {
        return $this->items;
    }

    public function setItems(array $items): self
    {
        $this->items = $items;

        return $this;
    }
    /**
     * @todo add some padded ID generation for external usage
     */
}
