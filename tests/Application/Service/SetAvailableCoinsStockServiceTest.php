<?php

namespace App\Tests\Application\Service;

use App\Application\Service\SetAvailableCoinsStockService;
use App\Domain\Model\AvailableCoin\Repository\AvailableCoinRepositoryInterface;
use PHPUnit\Framework\TestCase;

class SetAvailableCoinsStockServiceTest extends TestCase
{
    public function testGivenNoCoinsCoinsWhenExecuteIsCalledThenOnlyTheResetStockForAllMethodFromRepositoryIsCalled(): void
    {
        $service = new SetAvailableCoinsStockService($this->getAvailableCoinRepository([]));

        $service->execute([]);
    }

    public function testGivenSomeCoinsCoinsWhenExecuteIsCalledThenResetIsCalled(): void
    {
        $coinValues = [0.05, 0.25, 1];
        $service = new SetAvailableCoinsStockService($this->getAvailableCoinRepository($coinValues));

        $service->execute($coinValues);
    }

    private function getAvailableCoinRepository(array $coinValues): AvailableCoinRepositoryInterface
    {
        $mockedService = $this->createMock(AvailableCoinRepositoryInterface::class);
        $mockedService
            ->expects($this->once())
            ->method("resetStockForAll");
        $mockedService
            ->expects($this->exactly(sizeof($coinValues)))
            ->method("increaseStock");

        return $mockedService;
    }
}
