<?php

namespace App\Domain\Model\AvailableCoin\Entity;

use App\Infrastructure\Repository\DoctrineAvailableCoinRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DoctrineAvailableCoinRepository::class)
 */
class AvailableCoin
{
    public const RETURNABLE_COINS = [0.05, 0.1, 0.25];
    /**
     * @ORM\Id
     * @ORM\Column(type="float")
     */
    private float $coinValue;

    /**
     * @ORM\Column(type="integer", nullable=false)
     */
    private int $coinStock;

    /**
     * @ORM\Column(type="integer", nullable=false)
     */
    private int $coinCurrentlyInserted;

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

    public function increaseStock(): self
    {
        $this->coinStock++;

        return $this;
    }

    public function decreaseStock(): self
    {
        $this->coinStock--;

        return $this;
    }

    public function getCurrentlyInserted(): int
    {
        return $this->coinCurrentlyInserted;
    }

    public function setCurrentlyInserted(int $coinCurrentlyInserted): self
    {
        $this->coinCurrentlyInserted = $coinCurrentlyInserted;

        return $this;
    }

    public function increaseCurrentlyInserted(): self
    {
        $this->coinCurrentlyInserted++;

        return $this;
    }

    public function getTotalInsertedMoney(): float
    {
        $totalInsertedMoney = 0;

        for ($i = 0; $i < $this->getCurrentlyInserted(); $i++) {
            $totalInsertedMoney += $this->getCoinValue();
        }

        return $totalInsertedMoney;
    }

    public function getStockCoins(): array
    {
        $singleStockCoins = [];

        for ($i = 0; $i < $this->getCoinStock(); $i++) {
            $singleStockCoins[] = $this->getCoinValue();
        }

        return $singleStockCoins;
    }

}
