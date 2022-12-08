<?php declare(strict_types=1);

namespace App\Repositories\Coins;

use App\Models\Coin;
use App\Models\Collections\CoinCollection;
use GuzzleHttp\Client;

class CoinMarketCapApiCoinRepository implements CoinRepository
{
    public function getCoins(int $limit): CoinCollection
    {
        $client = new Client([
            'base_uri' => 'https://pro-api.coinmarketcap.com/',
            'headers' => ['X-CMC_PRO_API_KEY' => $_ENV['API_KEY']]
        ]);

        $response = $client->request('GET', 'v1/cryptocurrency/listings/latest', [
            'query' => ['limit' => $limit]
        ]);

        $coins = json_decode($response->getBody()->getContents(), true);

        $queryIds = [];

        foreach ($coins['data'] as $coin) {
            $queryIds[] = $coin['id'];
        }

        $response = $client->request('GET', 'v2/cryptocurrency/info', [
            'query' => ['id' => implode(',', $queryIds)]
        ]);
        $logos = json_decode($response->getBody()->getContents(), true);
        $logos = $logos['data'];

        $coinCollection = new CoinCollection();

        foreach ($coins['data'] as $coin) {
            $coinCollection->add(
                new Coin(
                    $coin['id'],
                    $coin['name'],
                    $coin['symbol'],
                    array_values(array_filter($logos, fn($logo) => $logo['id'] == $coin['id']))[0]['logo'],
                    $coin['quote']['USD']['price'],
                    $coin['quote']['USD']['percent_change_24h']
                )
            );
        }
        return $coinCollection;
    }
}
