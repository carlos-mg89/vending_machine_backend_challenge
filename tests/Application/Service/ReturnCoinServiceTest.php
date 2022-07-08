<?php

namespace App\Tests\Application\Service;

use App\Application\Service\ReturnCoinService;
use App\Domain\Model\AvailableCoin\Entity\AvailableCoin;
use App\Domain\Model\AvailableCoin\Repository\AvailableCoinRepositoryInterface;
use PHPUnit\Framework\TestCase;

class ReturnCoinServiceTest extends TestCase
{
    private const DUMMY_COIN_VALUE = 0.1;
    private const DUMMY2_COIN_VALUE = 0.25;

    public function testGivenOneInsertedCoinWhenExecuteIsCalledThenAvailableCoinRepositoryIncreaseStockIsCalled(): void
    {
        $expectedReturnedCoins = [self::DUMMY_COIN_VALUE];
        $insertedCoins = $this->getAvailableCoinAsArray();
        $availableCoinRepositoryMock = $this->getAvailableCoinRepository($insertedCoins);
        $service = new ReturnCoinService($availableCoinRepositoryMock);

        $responseReturnedCoins = $service->execute();

        $this->assertNotEmpty($responseReturnedCoins);
        $this->assertEquals($expectedReturnedCoins, $responseReturnedCoins);
    }

    public function testGivenTwoInsertedCoinWhenExecuteIsCalledThenAvailableCoinRepositoryIncreaseStockIsCalled(): void
    {
        $expectedReturnedCoins = [self::DUMMY_COIN_VALUE, self::DUMMY2_COIN_VALUE];
        $insertedCoins = $this->getAvailableCoinsAsArray();
        $availableCoinRepositoryMock = $this->getAvailableCoinRepository($insertedCoins);
        $service = new ReturnCoinService($availableCoinRepositoryMock);

        $responseReturnedCoins = $service->execute();

        $this->assertNotEmpty($responseReturnedCoins);
        $this->assertEquals($expectedReturnedCoins, $responseReturnedCoins);
    }

    private function getAvailableCoinAsArray(): array
    {
        $availableCoin = new AvailableCoin();
        $availableCoin
            ->setCoinValue(self::DUMMY_COIN_VALUE)
            ->setCoinStock(1)
            ->setCurrentlyInserted(1);

        return [$availableCoin];
    }

    private function getAvailableCoinsAsArray(): array
    {
        $availableCoin = new AvailableCoin();
        $availableCoin
            ->setCoinValue(self::DUMMY_COIN_VALUE)
            ->setCoinStock(1)
            ->setCurrentlyInserted(1);
        $availableCoin2 = new AvailableCoin();
        $availableCoin2
            ->setCoinValue(self::DUMMY2_COIN_VALUE)
            ->setCoinStock(1)
            ->setCurrentlyInserted(1);

        return [$availableCoin, $availableCoin2];
    }

    private function getAvailableCoinRepository(array $insertedCoins): AvailableCoinRepositoryInterface
    {
        $mockedRepository = $this->createMock(AvailableCoinRepositoryInterface::class);
        $mockedRepository
            ->expects($this->once())
            ->method("getAllCurrentlyInserted")
            ->willReturn($insertedCoins);
        $mockedRepository
            ->expects($this->once())
            ->method("resetAllCurrentlyInsertedAndDecreaseStock");

        return $mockedRepository;
    }
}
