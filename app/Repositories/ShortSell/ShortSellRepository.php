<?php declare(strict_types=1);

namespace App\Repositories\ShortSell;

use App\Models\Collections\ShortSellOrderCollection;
use App\Models\ShortSellOrder;
use App\Services\ShortCrypto\ShortSellOrderRequest;

interface ShortSellRepository
{
    public function getAllOpenShortSellOrders(int $userId): ?ShortSellOrderCollection;

    public function getAllClosedShortSellOrders(int $userId): ?ShortSellOrderCollection;

    public function create(ShortSellOrderRequest $shortSellOrderRequest): void;

    public function getOpenOrder(int $userId, int $coinId): ?ShortSellOrder;

    public function update(ShortSellOrder $shortSellOrder): void;
}
