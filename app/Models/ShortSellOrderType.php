<?php declare(strict_types=1);

namespace App\Models;

use MyCLabs\Enum\Enum;

/**
 * @method static ShortSellOrderType OPEN()
 * @method static ShortSellOrderType CLOSED()
 */
final class ShortSellOrderType extends Enum
{
    private const OPEN = 'open';
    private const CLOSED = 'closed';
}