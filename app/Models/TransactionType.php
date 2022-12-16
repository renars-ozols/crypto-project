<?php declare(strict_types=1);

namespace App\Models;

use MyCLabs\Enum\Enum;

/**
 * @method static TransactionType BUY()
 * @method static TransactionType SELL()
 */
final class TransactionType extends Enum
{
    private const BUY = 'buy';
    private const SELL = 'sell';
}
