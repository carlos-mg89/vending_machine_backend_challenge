<?php

namespace App\Application\Service;

use App\Domain\Model\AvailableCoin\Repository\AvailableCoinRepositoryInterface;

class InsertCoinService
{
    private AvailableCoinRepositoryInterface $availableCoinRepository;

    public function __construct(AvailableCoinRepositoryInterface $availableCoinRepository)
    {
        $this->availableCoinRepository = $availableCoinRepository;
    }

    public function execute(float $coinValue): void
    {
        $this->availableCoinRepository->increaseStock($coinValue);
    }
}
