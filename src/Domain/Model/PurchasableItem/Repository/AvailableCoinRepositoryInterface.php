<?php

namespace App\Domain\Model\PurchasableItem\Repository;

use App\Domain\Model\PurchasableItem\Entity\AvailableCoin;

interface AvailableCoinRepositoryInterface
{
    public function add(AvailableCoin $availableCoin, bool $flush = true): void;

    public function save(AvailableCoin $availableCoin, bool $flush = true): void;
}
