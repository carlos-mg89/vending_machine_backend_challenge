<?php

namespace App\Domain\Model\PurchasableItem\Repository;

use App\Domain\Model\PurchasableItem\Entity\PurchasableItem;

interface PurchasableItemRepositoryInterface
{
    public function add(PurchasableItem $entity, bool $flush = true): void;

    public function save(PurchasableItem $entity, bool $flush = true): void;

    public function remove(PurchasableItem $entity, bool $flush = true): void;

    public function findOneBySelector(string $selector): ?PurchasableItem;
}
