<?php

namespace App\Application\Service;

use App\Domain\Model\AvailableCoin\Repository\AvailableCoinRepositoryInterface;
use App\Domain\Model\PurchasableItem\Entity\PurchasableItem;
use App\Domain\Model\PurchasableItem\Exception\InsertedMoneyInsufficientToGetPurchasableItemException;
use App\Domain\Model\PurchasableItem\Exception\ItemOutOfStockException;
use App\Domain\Model\PurchasableItem\Exception\ItemUnknownException;
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
