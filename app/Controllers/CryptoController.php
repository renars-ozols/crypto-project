<?php declare(strict_types=1);

namespace App\Controllers;

use App\Services\IndexCoinService;
use App\Template;

class CryptoController
{
    public function index(): Template
    {
        $limit = 10;
        $coins = (new IndexCoinService())->execute($limit);
        return new Template('index.twig', ['coins' => $coins->getCoins()]);
    }
}
