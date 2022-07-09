<?php

namespace App\Tests\Application\Service;

use App\Application\Service\SetAvailableCoinsStockService;
use App\Application\Service\SetMachineStateService;
use App\Application\Service\SetPurchasableItemsStockService;
use PHPUnit\Framework\TestCase;

class SetMachineStateServiceTest extends TestCase
{
    public function testGivenTwoJsonArraysAsStringWhenExecuteIsCalledThenBothServicesWillBeCalledWithTheirRespectiveJsonArrays(): void
    {
        $service = new SetMachineStateService(
            $this->getSetPurchasableItemsStockService(),
            $this->getSetAvailableCoinsStockService(),
        );

        $service->execute("[]", "[]");
    }

    private function getSetPurchasableItemsStockService(): SetPurchasableItemsStockService
    {
        $mockedService = $this->createMock(SetPurchasableItemsStockService::class);
        $mockedService
            ->expects($this->once())
            ->method("execute");

        return $mockedService;
    }

    private function getSetAvailableCoinsStockService(): SetAvailableCoinsStockService
    {
        $mockedService = $this->createMock(SetAvailableCoinsStockService::class);
        $mockedService
            ->expects($this->once())
            ->method("execute");

        return $mockedService;
    }
}
