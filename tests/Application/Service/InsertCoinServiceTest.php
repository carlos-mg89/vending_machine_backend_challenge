<?php

namespace App\Tests\Application\Service;

use App\Application\Service\InsertCoinService;
use App\Domain\Model\AvailableCoin\Exception\CoinValueNotFound;
use App\Domain\Model\AvailableCoin\Repository\AvailableCoinRepositoryInterface;
use PHPUnit\Framework\TestCase;

class InsertCoinServiceTest extends TestCase
{
    private const VALID_COIN_VALUE = 1.0;
    private const INVALID_COIN_VALUE = 2.0;

    public function testGivenValidCoinValueWhenExecutedIsCalledThenAvailableCoinRepositoryIncreaseStockIsCalled(): void
    {
        $availableCoinRepositoryMock = $this->getAvailableCoinRepositoryWithValidCoin();
        $insertCoinService = new InsertCoinService($availableCoinRepositoryMock);

        $insertCoinService->execute(self::VALID_COIN_VALUE);
    }

    public function testGivenInvalidCoinValueWhenExecutedIsCalledThenAvailableCoinRepositoryIncreaseStockIsCalled(): void
    {
        $this->expectException(CoinValueNotFound::class);

        $availableCoinRepositoryMock = $this->getAvailableCoinRepositoryWithInvalidCoin();
        $insertCoinService = new InsertCoinService($availableCoinRepositoryMock);

        $insertCoinService->execute(self::INVALID_COIN_VALUE);
    }

    private function getAvailableCoinRepositoryWithValidCoin(): AvailableCoinRepositoryInterface
    {
        $mockedRepository = $this->createMock(AvailableCoinRepositoryInterface::class);
        $mockedRepository
            ->expects($this->once())
            ->method("increaseStock")
            ->with(self::VALID_COIN_VALUE);
        $mockedRepository
            ->expects($this->once())
            ->method("increaseCurrentlyInserted")
            ->with(self::VALID_COIN_VALUE);

        return $mockedRepository;
    }

    private function getAvailableCoinRepositoryWithInvalidCoin(): AvailableCoinRepositoryInterface
    {
        $mockedRepository = $this->createMock(AvailableCoinRepositoryInterface::class);
        $mockedRepository
            ->expects($this->once())
            ->method("increaseStock")
            ->with(self::INVALID_COIN_VALUE)
            ->willThrowException(new CoinValueNotFound());
        $mockedRepository
            ->expects($this->exactly(0))
            ->method("increaseCurrentlyInserted")
            ->with(self::INVALID_COIN_VALUE);

        return $mockedRepository;
    }
}
