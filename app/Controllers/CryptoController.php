<?php declare(strict_types=1);

namespace App\Controllers;

use App\Redirect;
use App\Services\Coins\SearchCoinService;
use App\Services\Coins\ShowCoinService;
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

    public function show(array $vars): Template
    {
        $coin = (new ShowCoinService())->execute($vars['id']);
        //$coin = [];
        return new Template('/coins/show.twig', ['coin' => $coin]);
    }

    public function search(): Redirect
    {
        $query = $_GET['query'];
        $coinId = (new SearchCoinService())->execute($query);
        if ($coinId) {
            return new Redirect("/coin/$coinId");
        }
        return new Redirect("/");
    }
}
