<?php

namespace App\Tests\Application\Service;

use App\Application\Service\PurchaseItemService;
use App\Domain\Model\AvailableCoin\Entity\AvailableCoin;
use App\Domain\Model\AvailableCoin\Repository\AvailableCoinRepositoryInterface;
use App\Domain\Model\PurchasableItem\Entity\PurchasableItem;
use App\Domain\Model\PurchasableItem\Exception\InsertedMoneyInsufficientToGetPurchasableItemException;
use App\Domain\Model\PurchasableItem\Exception\ItemOutOfStockException;
use App\Domain\Model\PurchasableItem\Exception\ItemUnknownException;
use App\Domain\Model\PurchasableItem\Repository\PurchasableItemRepositoryInterface;
use PHPUnit\Framework\TestCase;

class PurchaseItemServiceTest extends TestCase
{
    private const DUMMY_COIN_VALUE = 0.5;
    private const DUMMY_SELECTOR = "dummy-selector";
    private const DUMMY_ITEM_PRICE = 0.5;
    private const STOCK_ZERO = 0;
    private const STOCK_ONE = 1;
    private const AVAILABLE_COINS_INSERTED = 1;

    public function testGivenNullPurchasableItemWhenExecuteIsCalledThenExceptionIsThrown(): void
    {
        $this->expectException(ItemUnknownException::class);

        $availableCoinRepositoryMock = $this->getAvailableCoinRepositoryThatNoProcessesPurchases([]);
        $purchasableItemRepositoryMock = $this->getPurchasableItemRepository(null);
        $service = new PurchaseItemService($availableCoinRepositoryMock, $purchasableItemRepositoryMock);

        $service->execute(self::DUMMY_SELECTOR);
    }

    public function testGivenPurchasableItemWithoutStockWhenExecuteIsCalledThenExceptionIsThrown(): void
    {
        $this->expectException(ItemOutOfStockException::class);

        $availableCoinRepositoryMock = $this->getAvailableCoinRepositoryThatNoProcessesPurchases([]);
        $purchasableItem = $this->getPurchasableItem(self::STOCK_ZERO);
        $purchasableItemRepositoryMock = $this->getPurchasableItemRepository($purchasableItem);
        $service = new PurchaseItemService($availableCoinRepositoryMock, $purchasableItemRepositoryMock);

        $service->execute(self::DUMMY_SELECTOR);
    }

    public function testGivenInsertCoinsAreZeroWhenExecuteIsCalledThenExceptionIsThrown(): void
    {
        $this->expectException(InsertedMoneyInsufficientToGetPurchasableItemException::class);

        $availableCoins = $this->getAvailableCoins();
        $availableCoinRepositoryMock = $this->getAvailableCoinRepositoryThatNoProcessesPurchases($availableCoins);
        $purchasableItem = $this->getPurchasableItem(self::STOCK_ONE);
        $purchasableItemRepositoryMock = $this->getPurchasableItemRepository($purchasableItem);
        $service = new PurchaseItemService($availableCoinRepositoryMock, $purchasableItemRepositoryMock);

        $service->execute(self::DUMMY_SELECTOR);
    }

    public function testGivenEnoughInsertCoinsWhenExecuteIsCalledThenCurrentlyInsertedMoneyIsReset(): void
    {
        $availableCoins = $this->getAvailableCoins(self::AVAILABLE_COINS_INSERTED);
        $availableCoinRepositoryMock = $this->getAvailableCoinRepositoryWhichProcessesPurchases($availableCoins);
        $purchasableItem = $this->getPurchasableItem(self::STOCK_ONE);
        $purchasableItemRepositoryMock = $this->getPurchasableItemRepository($purchasableItem, true);
        $service = new PurchaseItemService($availableCoinRepositoryMock, $purchasableItemRepositoryMock);

        $response = $service->execute(self::DUMMY_SELECTOR);

        $this->assertEquals([self::DUMMY_SELECTOR], $response);
    }

    private function getAvailableCoinRepositoryThatNoProcessesPurchases(array $items): AvailableCoinRepositoryInterface
    {
        $mockedRepository = $this->createMock(AvailableCoinRepositoryInterface::class);
        $mockedRepository
            ->expects(empty($items) ? $this->exactly(0) : $this->once())
            ->method("getAllCurrentlyInserted")
            ->willReturn($items);

        return $mockedRepository;
    }

    private function getAvailableCoinRepositoryWhichProcessesPurchases(array $items): AvailableCoinRepositoryInterface
    {
        $mockedRepository = $this->createMock(AvailableCoinRepositoryInterface::class);
        $mockedRepository
            ->expects($this->exactly(2))
            ->method("getAllCurrentlyInserted")
            ->willReturn($items);
        $mockedRepository
            ->expects($this->once())
            ->method("resetAllCurrentlyInserted");

        return $mockedRepository;
    }

    private function getPurchasableItemRepository(
        ?PurchasableItem $item,
        bool $shouldProcessPurchase = false,
    ): PurchasableItemRepositoryInterface {
        $mockedRepository = $this->createMock(PurchasableItemRepositoryInterface::class);
        $mockedRepository
            ->expects($this->once())
            ->method("findOneBySelector")
            ->willReturn($item);
        $mockedRepository
            ->expects($shouldProcessPurchase ? $this->once() : $this->exactly(0))
            ->method("save");

        return $mockedRepository;
    }

    private function getPurchasableItem(int $stock): PurchasableItem
    {
        $purchasableItem = new PurchasableItem();
        $purchasableItem
            ->setSelector(self::DUMMY_SELECTOR)
            ->setPrice(self::DUMMY_ITEM_PRICE)
            ->setStock($stock)
        ;

        return $purchasableItem;
    }

    /**
     * @return AvailableCoin[]
     */
    private function getAvailableCoins(int $insertedCoins = 0): array
    {
        $availableCoin = new AvailableCoin();
        $availableCoin
            ->setCoinValue(self::DUMMY_COIN_VALUE)
            ->setCoinStock(1)
            ->setCurrentlyInserted($insertedCoins);

        return [$availableCoin];
    }
}
