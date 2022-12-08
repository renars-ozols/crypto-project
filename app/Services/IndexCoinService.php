<?php declare(strict_types=1);

namespace App\Services;

use App\Models\Collections\CoinCollection;
use App\Repositories\Coins\CoinMarketCapApiCoinRepository;
use App\Repositories\Coins\CoinRepository;

class IndexCoinService
{
    private CoinRepository $coinRepository;

    public function __construct()
    {
        $this->coinRepository = new CoinMarketCapApiCoinRepository();
    }

    public function execute(int $limit): CoinCollection
    {
        return $this->coinRepository->getCoins($limit);
    }
}
