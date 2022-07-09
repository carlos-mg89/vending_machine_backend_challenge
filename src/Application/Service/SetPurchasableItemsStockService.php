<?php

namespace App\Application\Service;

use App\Domain\Model\PurchasableItem\Repository\PurchasableItemRepositoryInterface;

class SetPurchasableItemsStockService
{
    private PurchasableItemRepositoryInterface $purchasableItemRepository;

    public function __construct(PurchasableItemRepositoryInterface $purchasableItemRepository)
    {
        $this->purchasableItemRepository = $purchasableItemRepository;
    }

    public function execute(array $jsonItemsStock): void
    {
        foreach ($jsonItemsStock as $jsonItem) {
            $purchasableItem = $this->purchasableItemRepository->findOneBySelector($jsonItem->selector);
            $purchasableItem->setStock($jsonItem->stock);
            $this->purchasableItemRepository->save($purchasableItem);
        }
    }
}
