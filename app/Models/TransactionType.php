<?php declare(strict_types=1);

namespace App\Models;

use MyCLabs\Enum\Enum;

/**
 * @method static TransactionType BUY()
 * @method static TransactionType SELL()
 * @method static TransactionType TRANSFER()
 * @method static TransactionType RECEIVED()
 */
final class TransactionType extends Enum
{
    private const BUY = 'buy';
    private const SELL = 'sell';
    private const TRANSFER = 'transfer';
    private const RECEIVED = 'received';
}
