<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\CartItemRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CartItemRepository::class)
 */
class CartItem
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer",options={"unsigned"=true})
     */
    private int $id = 0;

    /**
     * @ORM\ManyToOne(targetEntity=Product::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private Product $product;

    /**
     * @ORM\Column(type="integer")
     */
    private int $qty;

    /**
     * @ORM\Column(type="integer")
     */
    private int $price;

    /**
     * @ORM\ManyToOne(targetEntity=Order::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private ?Order $order = null;

    /**
     * @ORM\Column(type="integer",options={"unsigned"=true})
     */
    private int $owner = 0;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getQty(): ?int
    {
        return $this->qty;
    }

    public function setQty(int $qty): self
    {
        $this->qty = $qty;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getOrder(): ?Order
    {
        return $this->order;
    }

    public function setOrder(Order $order): self
    {
        $this->order = $order;

        return $this;
    }

    public function getOwner(): int
    {
        return $this->owner ?? 0;
    }

    public function setOwner(int $owner): self
    {
        $this->owner = $owner;

        return $this;
    }
}
