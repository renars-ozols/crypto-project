<?php declare(strict_types=1);

namespace App\Repositories\UserDashboard;

use App\Database;
use App\Models\Collections\PortfolioCollection;
use App\Models\Portfolio;
use App\Models\Transaction;
use App\Models\Collections\TransactionCollection;
use GuzzleHttp\Client;

class MySQLUserDashboardRepository implements UserDashboardRepository
{
    public function getPortfolio(string $userId): ?PortfolioCollection
    {
        $queryBuilder = Database::getConnection()->createQueryBuilder();
        $result = $queryBuilder->select('*')
            ->from('wallet')
            ->where('user_id = ?')
            ->setParameter(0, $userId)
            ->executeQuery()
            ->fetchAllAssociative();

        if ($result) {
            $currentPrices = $this->getCurrentPrices(implode(',', array_column($result, 'coin_id')));
            $averageBuyingPrices = $this->getAverageBuyingPrices($userId);

            $portfolio = new PortfolioCollection();
            foreach ($result as $entry) {
                $currentPrice = array_filter($currentPrices,
                    fn($coin) => $coin['id'] == $entry['coin_id'])[$entry['coin_id']]['quote']['USD']['price'];
                $averagePrice = array_values(array_filter($averageBuyingPrices,
                    fn($coin) => $coin['coin_id'] === $entry['coin_id']))[0]['average_price'];

                $portfolio->add(new Portfolio(
                    (int)$entry['id'],
                    (int)$entry['user_id'],
                    (int)$entry['coin_id'],
                    $entry['coin_name'],
                    $entry['coin_logo'],
                    (float)$entry['amount'],
                    (float)$averagePrice,
                    $currentPrice
                ));
            }

            return $portfolio;
        }
        return null;
    }

    private function getCurrentPrices(string $ids): array
    {
        $client = new Client([
            'base_uri' => 'https://pro-api.coinmarketcap.com/',
            'headers' => [
                'X-CMC_PRO_API_KEY' => $_ENV['API_KEY']
            ]
        ]);
        $response = $client->request('GET', 'v2/cryptocurrency/quotes/latest', [
            'query' => ['id' => $ids]
        ]);

        $response = json_decode($response->getBody()->getContents(), true);
        return $response['data'];
    }

    private function getAverageBuyingPrices(string $userId): array
    {
        $queryBuilder = Database::getConnection()->createQueryBuilder();

        return $queryBuilder->select('coin_id, AVG(coin_price) as average_price')
            ->from('transactions')
            ->where('user_id = ?')
            ->andWhere('type = ?')
            ->setParameter(0, $userId)
            ->setParameter(1, 'buy')
            ->groupBy('coin_id')
            ->executeQuery()
            ->fetchAllAssociative();
    }

    public function getTransactions(string $userId): ?TransactionCollection
    {
        $queryBuilder = Database::getConnection()->createQueryBuilder();
        $result = $queryBuilder->select('*')
            ->from('transactions')
            ->where('user_id = ?')
            ->setParameter(0, $userId)
            ->addOrderBy('created_at', 'DESC')
            ->executeQuery()
            ->fetchAllAssociative();

        if ($result) {
            $transactions = new TransactionCollection();
            foreach ($result as $transaction) {
                $transactions->addTransaction(new Transaction(
                    (int)$transaction['id'],
                    (int)$transaction['user_id'],
                    (int)$transaction['coin_id'],
                    $transaction['type'],
                    $transaction['coin_name'],
                    (float)$transaction['coin_price'],
                    (float)$transaction['amount'],
                    $transaction['created_at']
                ));
            }

            return $transactions;
        }
        return null;
    }
}
