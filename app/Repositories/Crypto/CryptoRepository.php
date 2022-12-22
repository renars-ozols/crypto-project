<?php declare(strict_types=1);

namespace App\Repositories\Crypto;

use App\Models\Crypto;
use App\Models\Collections\CryptoCollection;
use stdClass;

interface CryptoRepository
{
    public function getCoins(int $limit): CryptoCollection;

    public function getCoin(int $id): Crypto;

    public function getCoinBySymbol(string $symbol): Crypto;

    public function getCurrentPrices(string $ids): stdClass;
}
