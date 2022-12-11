<?php declare(strict_types=1);

namespace App\Repositories\UserDashboard;

use App\Authentication;
use App\Database;
use App\Models\Collections\PortfolioCollection;
use App\Models\Portfolio;
use App\Models\Transaction;
use App\Models\Collections\TransactionCollection;
use App\Models\TransactionType;
use App\Services\UserDashboard\BuySellCryptoServiceRequest;

class MySQLUserDashboardRepository implements UserDashboardRepository
{
    public function depositMoney(float $amount): void
    {
        $queryBuilder = Database::getConnection()->createQueryBuilder();
        $queryBuilder->update('users')
            ->set('money', "money + $amount")
            ->where('id = ?')
            ->setParameter(0, Authentication::getAuthId())
            ->executeQuery();
    }

    public function withdrawMoney(float $amount): void
    {
        $queryBuilder = Database::getConnection()->createQueryBuilder();
        $queryBuilder->update('users')
            ->set('money', "money - $amount")
            ->where('id = ?')
            ->setParameter(0, Authentication::getAuthId())
            ->executeQuery();
    }

    public function buyCrypto(BuySellCryptoServiceRequest $request): void
    {
        $amountToDeduct = $request->getAmount() * $request->getCoinPrice();
        $queryBuilder = Database::getConnection()->createQueryBuilder();
        $queryBuilder->update('users')
            ->set('money', "money - $amountToDeduct")
            ->where('id = ?')
            ->setParameter(0, Authentication::getAuthId())
            ->executeQuery();

        $queryBuilder = Database::getConnection()->createQueryBuilder();
        $result = $queryBuilder->update('wallet')
            ->set('amount', "amount + {$request->getAmount()}")
            ->where('coin_id = ?')
            ->andWhere('user_id = ?')
            ->setParameter(0, $request->getCoinId())
            ->setParameter(1, $request->getUserId())
            ->executeQuery();
//        echo "<pre>";
//        var_dump($result->rowCount());die;

        if ($result->rowCount() == 0) {
            $queryBuilder->resetQueryParts();
            $queryBuilder->insert('wallet')
                ->values([
                    'user_id' => ':user_id',
                    'coin_id' => ':coin_id',
                    'coin_name' => ':coin_name',
                    'coin_logo' => ':coin_logo',
                    'amount' => ':amount'
                ])
                ->setParameters([
                    'user_id' => $request->getUserId(),
                    'coin_id' => $request->getCoinId(),
                    'coin_name' => $request->getCoinName(),
                    'coin_logo' => $request->getCoinLogo(),
                    'amount' => $request->getAmount()
                ])
                ->executeQuery();
        }

//        echo "<pre>";
//        var_dump($request);die;

        $queryBuilder->insert('transactions')
            ->values([
                'user_id' => ':user_id',
                'coin_id' => ':coin_id',
                'type' => ':type',
                'coin_name' => ':coin_name',
                'coin_price' => ':coin_price',
                'amount' => ':amount'
            ])
            ->setParameters([
                'user_id' => $request->getUserId(),
                'coin_id' => $request->getCoinId(),
                'type' => TransactionType::BUY,
                'coin_name' => $request->getCoinName(),
                'coin_price' => $request->getCoinPrice(),
                'amount' => $request->getAmount()
            ])
            ->executeQuery();
    }

    public function sellCrypto(BuySellCryptoServiceRequest $request): void
    {
        $amountToAdd = $request->getAmount() * $request->getCoinPrice();
        $queryBuilder = Database::getConnection()->createQueryBuilder();
        $queryBuilder->update('users')
            ->set('money', "money + $amountToAdd")
            ->where('id = ?')
            ->setParameter(0, Authentication::getAuthId())
            ->executeQuery();

        $queryBuilder->resetQueryParts();

        $queryBuilder->update('wallet')
            ->set('amount', "amount - {$request->getAmount()}")
            ->where('coin_id = ?')
            ->andWhere('user_id = ?')
            ->setParameter(0, $request->getCoinId())
            ->setParameter(1, $request->getUserId())
            ->executeQuery();

        $queryBuilder->resetQueryParts();

        $amountLeft = $queryBuilder->select('amount')
            ->from('wallet')
            ->where('coin_id = ?')
            ->andWhere('user_id = ?')
            ->setParameter(0, $request->getCoinId())
            ->setParameter(1, $request->getUserId())
            ->executeQuery()
            ->fetchAssociative();

        if ($amountLeft['amount'] == 0) {
            $queryBuilder->resetQueryParts();
            $queryBuilder->delete('wallet')
                ->where('coin_id = ?')
                ->andWhere('user_id = ?')
                ->setParameter(0, $request->getCoinId())
                ->setParameter(1, $request->getUserId())
                ->executeQuery();
        }

        $queryBuilder->insert('transactions')
            ->values([
                'user_id' => ':user_id',
                'coin_id' => ':coin_id',
                'type' => ':type',
                'coin_name' => ':coin_name',
                'coin_price' => ':coin_price',
                'amount' => ':amount'
            ])
            ->setParameters([
                'user_id' => $request->getUserId(),
                'coin_id' => $request->getCoinId(),
                'type' => TransactionType::SELL,
                'coin_name' => $request->getCoinName(),
                'coin_price' => $request->getCoinPrice(),
                'amount' => $request->getAmount()
            ])
            ->executeQuery();
    }

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
            $portfolio = new PortfolioCollection();
            foreach ($result as $entry) {
                $portfolio->add(new Portfolio(
                    (int) $entry['id'],
                    (int) $entry['user_id'],
                    (int) $entry['coin_id'],
                    $entry['coin_name'],
                    $entry['coin_logo'],
                    (float) $entry['amount']
                ));
            }

            return $portfolio;
        }
        return null;
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
                    (int) $transaction['id'],
                    (int) $transaction['user_id'],
                    (int) $transaction['coin_id'],
                    $transaction['type'],
                    $transaction['coin_name'],
                    (float) $transaction['coin_price'],
                    (float) $transaction['amount'],
                    $transaction['created_at']
                ));
            }

            return $transactions;
        }
        return null;
    }
}
