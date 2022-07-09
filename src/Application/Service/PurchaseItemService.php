<?php

namespace App\Application\Service;

use App\Domain\Model\AvailableCoin\Repository\AvailableCoinRepositoryInterface;
use App\Domain\Model\PurchasableItem\Entity\PurchasableItem;
use App\Domain\Model\PurchasableItem\Exception\InsertedMoneyInsufficientToGetPurchasableItemException;
use App\Domain\Model\PurchasableItem\Exception\ItemOutOfStockException;
use App\Domain\Model\PurchasableItem\Exception\ItemUnknownException;
use App\Domain\Model\PurchasableItem\Repository\PurchasableItemRepositoryInterface;

class PurchaseItemService
{
    private AvailableCoinRepositoryInterface $availableCoinRepository;
    private PurchasableItemRepositoryInterface $purchasableItemRepository;

    public function __construct(
        AvailableCoinRepositoryInterface $availableCoinRepository,
        PurchasableItemRepositoryInterface $purchasableItemRepository,
    ) {
        $this->availableCoinRepository = $availableCoinRepository;
        $this->purchasableItemRepository = $purchasableItemRepository;
    }

    /**
     * @throws ItemOutOfStockException
     * @throws ItemUnknownException
     * @throws InsertedMoneyInsufficientToGetPurchasableItemException
     */
    public function execute(string $selector): array
    {
        $purchasableItem = $this->purchasableItemRepository->findOneBySelector($selector);

        $this->throwExceptionsIfNeeded($purchasableItem, $selector);
        $this->throwExceptionIfInsufficientMoneyToPurchaseItem($purchasableItem);

        $changeCoins = $this->processValidPurchaseAndGetChangeCoins($purchasableItem);

        return array_merge([$selector], $changeCoins);
    }

    /**
     * @throws ItemOutOfStockException
     * @throws ItemUnknownException
     */
    private function throwExceptionsIfNeeded(
        ?PurchasableItem $selectedPurchasableItem,
        string $selector,
    ): void {
        if ($selectedPurchasableItem == null) {
            throw new ItemUnknownException($selector);
        } elseif (0 == $selectedPurchasableItem->getStock()) {
            throw new ItemOutOfStockException($selector);
        }
    }

    /**
     * @throws InsertedMoneyInsufficientToGetPurchasableItemException
     */
    private function throwExceptionIfInsufficientMoneyToPurchaseItem(PurchasableItem $purchasableItem): void
    {
        $totalInsertedMoney = $this->getTotalInsertedMoney();

        if ($totalInsertedMoney < $purchasableItem->getPrice()) {
            throw new InsertedMoneyInsufficientToGetPurchasableItemException($purchasableItem->getSelector());
        }
    }

    private function getTotalInsertedMoney(): float
    {
        $insertedAvailableCoins = $this->availableCoinRepository->getAllCurrentlyInserted();
        $totalInsertedMoney = 0;

        foreach ($insertedAvailableCoins as $insertedAvailableCoin) {
            $totalInsertedMoney += $insertedAvailableCoin->getTotalInsertedMoney();
        }

        return $totalInsertedMoney;
    }

    private function getAllAvailableCoinsSortedFromHigherToLower(): array
    {
        $availableCoins = $this->availableCoinRepository->getAllWithStock();
        $singleAvailableCoins = [];

        foreach ($availableCoins as $insertedAvailableCoin) {
            $singleAvailableCoins = array_merge($singleAvailableCoins, $insertedAvailableCoin->getStockCoins());
        }

        rsort($singleAvailableCoins);

        return $singleAvailableCoins;
    }

    private function processValidPurchaseAndGetChangeCoins(PurchasableItem $purchasableItem): array
    {
        $this->decreaseStockOfItem($purchasableItem);
        $changeCoins = $this->getChangeCoins($purchasableItem);
        $this->decreaseStockOfAvailableCoins($changeCoins);
        $this->availableCoinRepository->resetAllCurrentlyInserted();

        return $changeCoins;
    }

    private function decreaseStockOfItem(PurchasableItem $purchasableItem): void
    {
        $purchasableItem->decreaseStock();
        $this->purchasableItemRepository->save($purchasableItem);
    }

    private function getTotalChange(PurchasableItem $purchasableItem): float
    {
        $totalInsertedMoney = $this->getTotalInsertedMoney();

        return abs($purchasableItem->getPrice() - $totalInsertedMoney);
    }

    private function getChangeCoins(PurchasableItem $purchasableItem): array
    {
        $changeCoins = [];
        $allAvailableCoins = $this->getAllAvailableCoinsSortedFromHigherToLower();
        $totalChange = $this->getTotalChange($purchasableItem);

        while ($totalChange > 0) {
            $isCoinTaken = false;

            foreach ($allAvailableCoins as $coinValue) {
                if ($this->isCoinValueLessOrEqualThanTotalChange($coinValue, $totalChange)) {
                    $totalChange -= $coinValue;
                    $changeCoins[] = $coinValue;
                    $isCoinTaken = true;
                    $this->removeCoinFromAvailableCoinsArray($allAvailableCoins, $coinValue);
                }
            }

            if (!$isCoinTaken) {
                $totalChange = 0;
            }
        }

        return $changeCoins;
    }

    private function isCoinValueLessOrEqualThanTotalChange(float $coinValue, float $totalChange): bool
    {
        return $coinValue < $totalChange
            || abs($totalChange - $coinValue) < PHP_FLOAT_EPSILON;
    }

    private function decreaseStockOfAvailableCoins(array $changeCoins): void
    {
        foreach ($changeCoins as $coinValue) {
            $this->availableCoinRepository->decreaseStock($coinValue);
        }
    }

    private function removeCoinFromAvailableCoinsArray(array &$allAvailableCoins, $coinValueToRemove): void
    {
        $indexCoinToRemove = array_search($coinValueToRemove, $allAvailableCoins);
        unset($allAvailableCoins[$indexCoinToRemove]);
    }
}
