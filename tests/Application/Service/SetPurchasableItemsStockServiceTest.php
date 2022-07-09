<?php

namespace App\Tests\Application\Service;

use App\Application\Service\SetPurchasableItemsStockService;
use App\Domain\Model\PurchasableItem\Entity\PurchasableItem;
use App\Domain\Model\PurchasableItem\Repository\PurchasableItemRepositoryInterface;
use PHPUnit\Framework\TestCase;
use stdClass;

class SetPurchasableItemsStockServiceTest extends TestCase
{
    public function testGivenNoCoinsCoinsWhenExecuteIsCalledThenOnlyTheResetStockForAllMethodFromRepositoryIsCalled(): void
    {
        $service = new SetPurchasableItemsStockService($this->getPurchasableItemRepository([]));

        $service->execute([]);
    }

    public function testGivenSomeCoinsCoinsWhenExecuteIsCalledThenResetIsCalled(): void
    {
        $itemsWithStock = $this->getItemsWithStock();

        $service = new SetPurchasableItemsStockService(
            $this->getPurchasableItemRepository($itemsWithStock, new PurchasableItem())
        );

        $service->execute($itemsWithStock);
    }

    private function getItemsWithStock(): array
    {
        $itemWithStock = new stdClass();
        $itemWithStock->selector = "selector";
        $itemWithStock->stock = 5;

        return [$itemWithStock];
    }

    private function getPurchasableItemRepository(
        array $itemsWithStock,
        ?PurchasableItem $purchasableItem = null
    ): PurchasableItemRepositoryInterface {
        $mockedService = $this->createMock(PurchasableItemRepositoryInterface::class);
        $mockedService
            ->expects($this->exactly(sizeof($itemsWithStock)))
            ->method("findOneBySelector")
            ->willReturn($purchasableItem);
        $mockedService
            ->expects($this->exactly(sizeof($itemsWithStock)))
            ->method("save");

        return $mockedService;
    }
}
