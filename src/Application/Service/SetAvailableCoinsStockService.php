<?php

namespace App\Application\Service;

use App\Domain\Model\AvailableCoin\Repository\AvailableCoinRepositoryInterface;

class SetAvailableCoinsStockService
{
    private AvailableCoinRepositoryInterface $availableCoinRepository;

    public function __construct(AvailableCoinRepositoryInterface $availableCoinRepository)
    {
        $this->availableCoinRepository = $availableCoinRepository;
    }

    public function execute(array $coinValues): void
    {
        $this->availableCoinRepository->resetStockForAll();

        foreach ($coinValues as $coinValue) {
            $this->availableCoinRepository->increaseStock($coinValue);
        }
    }
}
