<?php declare(strict_types=1);

namespace App\Repositories\Coins;

use App\Models\Collections\CoinCollection;

interface CoinRepository
{
    public function getCoins(int $limit): CoinCollection;
}
