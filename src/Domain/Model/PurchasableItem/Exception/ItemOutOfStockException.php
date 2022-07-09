<?php

namespace App\Domain\Model\PurchasableItem\Exception;

use Exception;
use Throwable;

class ItemOutOfStockException extends Exception
{
    private const PREFIX_MESSAGE = "Out of stock: ";

    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct(self::PREFIX_MESSAGE . $message, $code, $previous);
    }
}
