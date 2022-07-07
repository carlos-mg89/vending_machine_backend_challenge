<?php

namespace App\Domain\Model\PurchasableItem\Entity;

use App\Infrastructure\Repository\DoctrinePurchasableItemRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DoctrinePurchasableItemRepository::class)
 */
class PurchasableItem
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=false, unique=true)
     */
    private string $selector;

    /**
     * @ORM\Column(type="float", nullable=false)
     */
    private float $price;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSelector(): string
    {
        return $this->selector;
    }

    public function setSelector(string $selector): self
    {
        $this->selector = $selector;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): self
    {
        $this->price = $price;

        return $this;
    }

}
