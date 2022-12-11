<?php declare(strict_types=1);

namespace App\Services\Coins;

use App\Repositories\Coins\CoinMarketCapApiCoinRepository;
use App\Repositories\Coins\CoinRepository;

class SearchCoinService
{
    private CoinRepository $coinRepository;

    public function __construct()
    {
        $this->coinRepository = new CoinMarketCapApiCoinRepository();
    }

    public function execute(string $query): int
    {
        return $this->coinRepository->searchCoin($query);
    }
}
