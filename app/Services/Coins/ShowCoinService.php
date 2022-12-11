<?php declare(strict_types=1);

namespace App\Services\Coins;

use App\Models\Coin;
use App\Repositories\Coins\CoinMarketCapApiCoinRepository;
use App\Repositories\Coins\CoinRepository;

class ShowCoinService
{
    private CoinRepository $coinRepository;

    public function __construct()
    {
        $this->coinRepository = new CoinMarketCapApiCoinRepository();
    }

    public function execute(string $id): Coin
    {
       return $this->coinRepository->getCoin($id);
    }
}