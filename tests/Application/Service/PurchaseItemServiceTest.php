<?php

namespace App\Tests\Application\Service;

use App\Application\Service\PurchaseItemService;
use App\Domain\Model\AvailableCoin\Repository\AvailableCoinRepositoryInterface;
use App\Domain\Model\PurchasableItem\Entity\PurchasableItem;
use App\Domain\Model\PurchasableItem\Exception\ItemOutOfStockException;
use App\Domain\Model\PurchasableItem\Exception\ItemUnknownException;
use App\Domain\Model\PurchasableItem\Repository\PurchasableItemRepositoryInterface;
use PHPUnit\Framework\TestCase;

class PurchaseItemServiceTest extends TestCase
{
    private const DUMMY_SELECTOR = "dummy-selector";
    private const DUMMY_ITEM_PRICE = 1.5;
    private const STOCK_ZERO = 0;

    public function testGivenNullPurchasableItemWhenExecuteIsCalledThenExceptionIsThrown(): void
    {
        $this->expectException(ItemUnknownException::class);

        $availableCoinRepositoryMock = $this->getAvailableCoinRepository();
        $purchasableItemRepositoryMock = $this->getPurchasableItemRepository(null);
        $service = new PurchaseItemService($availableCoinRepositoryMock, $purchasableItemRepositoryMock);

        $service->execute(self::DUMMY_SELECTOR);
    }

    public function testGivenPurchasableItemWithoutStockWhenExecuteIsCalledThenExceptionIsThrown(): void
    {
        $this->expectException(ItemOutOfStockException::class);

        $availableCoinRepositoryMock = $this->getAvailableCoinRepository();
        $purchasableItem = $this->getPurchasableItem();
        $purchasableItemRepositoryMock = $this->getPurchasableItemRepository($purchasableItem);
        $service = new PurchaseItemService($availableCoinRepositoryMock, $purchasableItemRepositoryMock);

        $service->execute(self::DUMMY_SELECTOR);
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

    private function getPurchasableItem(): PurchasableItem
    {
        $purchasableItem = new PurchasableItem();
        $purchasableItem
            ->setSelector(self::DUMMY_SELECTOR)
            ->setPrice(self::DUMMY_ITEM_PRICE)
            ->setStock(self::STOCK_ZERO)
        ;

        return $purchasableItem;
    }
}
