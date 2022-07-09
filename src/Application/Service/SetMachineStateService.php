<?php

namespace App\Application\Service;

class SetMachineStateService
{
    private SetPurchasableItemsStockService $setPurchasableItemsStockService;
    private SetAvailableCoinsStockService $setAvailableCoinsStockService;

    public function __construct(
        SetPurchasableItemsStockService $setPurchasableItemsStockService,
        SetAvailableCoinsStockService $setAvailableCoinsStockService,
    ) {
        $this->setPurchasableItemsStockService = $setPurchasableItemsStockService;
        $this->setAvailableCoinsStockService = $setAvailableCoinsStockService;
    }

    public function execute(string $itemsStockJson, string $availableCoinsJson): void
    {
        $itemsStockValues = json_decode($itemsStockJson);
        $availableCoins = json_decode($availableCoinsJson);

        $this->setPurchasableItemsStockService->execute($itemsStockValues);
        $this->setAvailableCoinsStockService->execute($availableCoins);
    }
}