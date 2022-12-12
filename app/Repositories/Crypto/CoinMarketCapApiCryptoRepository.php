<?php declare(strict_types=1);

namespace App\Repositories\Crypto;

use App\Models\Crypto;
use App\Models\Collections\CryptoCollection;
use GuzzleHttp\Client;

class CoinMarketCapApiCryptoRepository implements CryptoRepository
{
    private Client $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://pro-api.coinmarketcap.com/',
            'headers' => [
                'X-CMC_PRO_API_KEY' => $_ENV['API_KEY']
            ]
        ]);
    }

    public function getCoins(int $limit): CryptoCollection
    {
        $response = $this->client->request('GET', 'v1/cryptocurrency/listings/latest', [
            'query' => ['limit' => $limit]
        ]);

        $coins = json_decode($response->getBody()->getContents(), true);

        $queryIds = [];

        foreach ($coins['data'] as $coin) {
            $queryIds[] = $coin['id'];
        }

        $response = $this->client->request('GET', 'v2/cryptocurrency/info', [
            'query' => ['id' => implode(',', $queryIds)]
        ]);
        $logos = json_decode($response->getBody()->getContents(), true);
        $logos = $logos['data'];

        $coinCollection = new CryptoCollection();

        foreach ($coins['data'] as $coin) {
            $coinCollection->add(
                new Crypto(
                    $coin['id'],
                    $coin['name'],
                    $coin['symbol'],
                    array_values(array_filter($logos, fn($logo) => $logo['id'] == $coin['id']))[0]['logo'],
                    $coin['quote']['USD']['price'],
                    $coin['quote']['USD']['percent_change_1h'],
                    $coin['quote']['USD']['percent_change_24h'],
                    $coin['quote']['USD']['percent_change_7d']
                )
            );
        }
        return $coinCollection;
    }

    public function getCoin(string $id): Crypto
    {
        $response = $this->client->request('GET', 'v2/cryptocurrency/quotes/latest', [
            'query' => ['id' => $id]
        ]);

        $coin = json_decode($response->getBody()->getContents(), true);

        $response = $this->client->request('GET', 'v2/cryptocurrency/info', [
            'query' => ['id' => $id]
        ]);

        $logo = json_decode($response->getBody()->getContents(), true);

        return new Crypto(
            $coin['data'][$id]['id'],
            $coin['data'][$id]['name'],
            $coin['data'][$id]['symbol'],
            array_values($logo['data'])[0]['logo'],
            $coin['data'][$id]['quote']['USD']['price'],
            $coin['data'][$id]['quote']['USD']['percent_change_1h'],
            $coin['data'][$id]['quote']['USD']['percent_change_24h'],
            $coin['data'][$id]['quote']['USD']['percent_change_7d']
        );
    }

    public function searchCoin(string $query): int
    {
        $response = $this->client->request('GET', 'v1/cryptocurrency/map', [
            'query' => ['symbol' => $query]
        ]);

        $coin = json_decode($response->getBody()->getContents(), true);

        return $coin['data'][0]['id'];
    }
}
