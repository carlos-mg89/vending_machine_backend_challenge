<?php

namespace App\Tests\Application\Service;

use App\Application\Service\PurchaseItemService;
use App\Domain\Model\AvailableCoin\Repository\AvailableCoinRepositoryInterface;
use App\Domain\Model\PurchasableItem\Entity\PurchasableItem;
use App\Domain\Model\PurchasableItem\Exception\ItemUnknownException;
use App\Domain\Model\PurchasableItem\Repository\PurchasableItemRepositoryInterface;
use PHPUnit\Framework\TestCase;

class PurchaseItemServiceTest extends TestCase
{
    private const DUMMY_COIN_VALUE = 0.1;

    public function testGivenNullPurchasableItemWhenExecuteIsCalledThenExceptionIsThrown(): void
    {
        $this->expectException(ItemUnknownException::class);

        $availableCoinRepositoryMock = $this->getAvailableCoinRepository();
        $purchasableItemRepositoryMock = $this->getPurchasableItemRepository(null);
        $service = new PurchaseItemService($availableCoinRepositoryMock, $purchasableItemRepositoryMock);

        $responseReturnedCoins = $service->execute("dummy-selector");

        $this->assertEquals("", $responseReturnedCoins);
    }

    private function getAvailableCoinRepository(): AvailableCoinRepositoryInterface
    {
        $mockedRepository = $this->createMock(AvailableCoinRepositoryInterface::class);

        return $mockedRepository;
    }

    private function getPurchasableItemRepository(?PurchasableItem $item): PurchasableItemRepositoryInterface
    {
        $mockedRepository = $this->createMock(PurchasableItemRepositoryInterface::class);
        $mockedRepository
            ->expects($this->once())
            ->method("findOneBySelector")
            ->willReturn($item);

        return $mockedRepository;
    }
}
