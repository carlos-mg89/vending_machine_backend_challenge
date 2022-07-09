<?php

namespace App\Application\Service;

use App\Domain\Model\AvailableCoin\Repository\AvailableCoinRepositoryInterface;

class ReturnCoinService
{
    private AvailableCoinRepositoryInterface $availableCoinRepository;

    public function __construct(AvailableCoinRepositoryInterface $availableCoinRepository)
    {
        $this->availableCoinRepository = $availableCoinRepository;
    }

    public function execute(): array
    {
        $currentlyInsertedCoins = $this->getInsertedCoinsSeparately();
        $this->availableCoinRepository->resetAllCurrentlyInsertedAndDecreaseStock();

        return $currentlyInsertedCoins;
    }

    private function getInsertedCoinsSeparately(): array
    {
        $insertedCoins = [];
        $currentlyInsertedAvailableCoins = $this->availableCoinRepository->getAllCurrentlyInserted();

        foreach ($currentlyInsertedAvailableCoins as $availableCoin) {
            for ($i = 0; $i < $availableCoin->getCurrentlyInserted(); $i++) {
                $insertedCoins[] = $availableCoin->getCoinValue();
            }
        }

        return $insertedCoins;
    }
}
