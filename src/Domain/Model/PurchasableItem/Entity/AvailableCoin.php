<?php

namespace App\Domain\Model\PurchasableItem\Entity;

use App\Infrastructure\Repository\DoctrineAvailableCoinRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DoctrineAvailableCoinRepository::class)
 */
class AvailableCoin
{
    /**
     * @ORM\Id
     * @ORM\Column(type="float")
     */
    private float $coinValue;

    /**
     * @ORM\Column(type="integer", nullable=false)
     */
    private int $coinStock;

    public function getCoinValue(): float
    {
        return $this->coinValue;
    }

    public function setCoinValue(float $coinValue): self
    {
        $this->coinValue = $coinValue;

        return $this;
    }

    public function getCoinStock(): int
    {
        return $this->coinStock;
    }

    public function setCoinStock(int $coinStock): self
    {
        $this->coinStock = $coinStock;

        return $this;
    }

}
