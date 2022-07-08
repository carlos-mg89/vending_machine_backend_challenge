<?php

namespace App\Tests\Application\Service;

use App\Application\Service\InsertCoinService;
use App\Domain\Model\PurchasableItem\Repository\AvailableCoinRepositoryInterface;
use PHPUnit\Framework\TestCase;

class InsertCoinServiceTest extends TestCase
{
    private const ONE_EURO = 1.0;

    public function testGivenACoinValueWhenExecutedIsCalledThenAvailableCoinRepositoryIncreaseStockIsCalled(): void
    {
        $availableCoinRepositoryMock = $this->getAvailableCoinRepository(self::ONE_EURO);
        $insertCoinService = new InsertCoinService($availableCoinRepositoryMock);

        $insertCoinService->execute(self::ONE_EURO);
    }

    private function getAvailableCoinRepository(float $coinValue): AvailableCoinRepositoryInterface
    {
        $mockedRepository = $this->createMock(AvailableCoinRepositoryInterface::class);
        $mockedRepository
            ->expects($this->once())
            ->method("increaseStock")
            ->with($coinValue);

        return $mockedRepository;
    }
}
