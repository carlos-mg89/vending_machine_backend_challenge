<?php

namespace App\Domain\Model\PurchasableItem\Exception;

use Throwable;

class InsertedMoneyInsufficientToGetPurchasableItemException extends \Exception
{
    private const PREFIX_MESSAGE = "Item cannot be purchased with current inserted money: ";

    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct(self::PREFIX_MESSAGE . $message, $code, $previous);
    }
}
