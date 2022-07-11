<?php

namespace App\Tests\Domain\Model\AvailableCoin\Entity;

use App\Domain\Model\AvailableCoin\Entity\AvailableCoin;
use PHPUnit\Framework\TestCase;

class AvailableCoinTest extends TestCase
{
    public function testGivenAnAvailableCoinWithNoStockWhenIncreaseStockIsCalledThenStockWillBeIncreased(): void
    {
        $availableCoin = new AvailableCoin();
        $availableCoin->setCoinStock(0);
        $stockPriorIncrease = $availableCoin->getCoinStock();

        $availableCoin->increaseStock();
        $stockAfterIncrease = $availableCoin->getCoinStock();

        $this->assertEquals($stockPriorIncrease + 1, $stockAfterIncrease);
    }

    public function testGivenAnAvailableCoinWithOneStockWhenDecreaseStockIsCalledThenStockWillBeDecreased(): void
    {
        $availableCoin = new AvailableCoin();
        $availableCoin->setCoinStock(1);
        $stockPriorDecrease = $availableCoin->getCoinStock();

        $availableCoin->decreaseStock();
        $stockAfterDecrease = $availableCoin->getCoinStock();

        $this->assertEquals($stockPriorDecrease, $stockAfterDecrease + 1);
    }

    public function testGivenAnAvailableCoinWithInsertedMoneyWhenGetTotalInsertedMoneyIsCalledThenReturnsExpectedValue(): void
    {
        $coinValue = 0.25;
        $currentlyInserted = 8;
        $expectedValue = $coinValue * $currentlyInserted;
        $availableCoin = new AvailableCoin();
        $availableCoin->setCoinValue($coinValue);
        $availableCoin->setCurrentlyInserted($currentlyInserted);

        $totalInsertedMoney = $availableCoin->getTotalInsertedMoney();

        $this->assertEquals($expectedValue, $totalInsertedMoney);
    }

    public function testGivenAnAvailableCoinWithStockCoinsWhenGetStockCoinsIsCalledThenReturnsExpectedValue(): void
    {
        $coinValue = 0.35;
        $currentStock = 3;
        $expectedValue = [0.35, 0.35, 0.35];
        $availableCoin = new AvailableCoin();
        $availableCoin->setCoinValue($coinValue);
        $availableCoin->setCoinStock($currentStock);

        $stockCoinsValue = $availableCoin->getStockCoins();

        $this->assertEquals($expectedValue, $stockCoinsValue);
    }
}
