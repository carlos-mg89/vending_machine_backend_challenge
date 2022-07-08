<?php

namespace App\Domain\Model\AvailableCoin\Repository;

use App\Domain\Model\AvailableCoin\Entity\AvailableCoin;

interface AvailableCoinRepositoryInterface
{
    public function add(AvailableCoin $availableCoin, bool $flush = true): void;

    public function save(AvailableCoin $availableCoin, bool $flush = true): void;

    public function increaseStock(float $coinValue): void;
}