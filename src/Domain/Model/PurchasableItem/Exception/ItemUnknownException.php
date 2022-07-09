<?php

namespace App\Domain\Model\PurchasableItem\Exception;

use Throwable;

class ItemUnknownException extends \Exception
{
    private const PREFIX_MESSAGE = "Item does not exist: ";

    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct(self::PREFIX_MESSAGE . $message, $code, $previous);
    }
}
