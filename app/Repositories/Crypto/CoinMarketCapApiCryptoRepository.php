<?php declare(strict_types=1);

namespace App\Repositories\Crypto;

use App\Models\Crypto;
use App\Models\Collections\CryptoCollection;
use GuzzleHttp\Client;
use stdClass;

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

    private function fetch(string $url, array $query): stdClass
    {
        $response = $this->client->request('GET', $url, [
            'query' => $query
        ]);

        return json_decode($response->getBody()->getContents());
    }

    private function buildModel(stdClass $coin): Crypto
    {
        return new Crypto(
            $coin->id,
            $coin->name,
            $coin->symbol,
            $coin->logo,
            $coin->quote->USD->price,
            $coin->quote->USD->percent_change_1h,
            $coin->quote->USD->percent_change_24h,
            $coin->quote->USD->percent_change_7d
        );
    }

    public function getCoins(int $limit): CryptoCollection
    {
        $coins = $this->fetch('v1/cryptocurrency/listings/latest', ['limit' => $limit]);

        $queryIds = [];

        foreach ($coins->data as $coin) {
            $queryIds[] = $coin->id;
        }

        $logos = $this->fetch('v2/cryptocurrency/info', ['id' => implode(',', $queryIds)]);

        $coinCollection = new CryptoCollection();

        foreach ($coins->data as $coin) {
            $coin->logo = $logos->data->{$coin->id}->logo;
            $coinCollection->add($this->buildModel($coin));
        }
        return $coinCollection;
    }

    public function getCoin(int $id): Crypto
    {
        $coin = $this->fetch('v2/cryptocurrency/quotes/latest', ['id' => $id]);
        $logo = $this->fetch('v2/cryptocurrency/info', ['id' => $id]);
        $coin = $coin->data->{$id};
        $coin->logo = $logo->data->{$coin->id}->logo;

        return $this->buildModel($coin);
    }

    public function searchCoin(string $query): int
    {
        $coin = $this->fetch('v1/cryptocurrency/map', ['symbol' => $query]);
        return $coin->data[0]->id;
    }

    public function getCurrentPrices(string $ids): stdClass
    {
        return $this->fetch('v2/cryptocurrency/quotes/latest', ['id' => $ids]);
    }
}
