<?php declare(strict_types=1);

namespace App\Models\Collections;

use App\Models\ShortSellOrder;

class ShortSellOrderCollection
{
    private array $shortSellOrders;

    public function __construct(array $shortSellOrders = [])
    {
        foreach ($shortSellOrders as $entry) {
            $this->add($entry);
        }
    }

    public function add(ShortSellOrder $shortSellOrder): void
    {
        $this->shortSellOrders[] = $shortSellOrder;
    }

    public function getAll(): array
    {
        return $this->shortSellOrders;
    }
}
