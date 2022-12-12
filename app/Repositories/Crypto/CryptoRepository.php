<?php declare(strict_types=1);

namespace App\Repositories\Crypto;

use App\Models\Crypto;
use App\Models\Collections\CryptoCollection;

interface CryptoRepository
{
    public function getCoins(int $limit): CryptoCollection;
    public function getCoin(string $id): Crypto;
    public function searchCoin(string $query): int;
}
